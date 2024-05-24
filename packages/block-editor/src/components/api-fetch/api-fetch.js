import axios from 'axios';
import * as MockData from './mock-data';

const requests = {
  getBlock: {
    method: 'GET',
    regex: /\/wp\/v2\/blocks\/(\d*)/g,
    run: getBlock,
  },
  getBlocks: {
    method: 'GET',
    regex: /\/wp\/v2\/blocks($|[?].*)/g,
    run: getBlocks,
  },
  postBlocks: {
    method: 'POST',
    regex: /\/wp\/v2\/blocks.*/g,
    run: postBlocks,
  },
  putBlock: {
    method: 'PUT',
    regex: /\/wp\/v2\/blocks\/(\d*)/g,
    run: putBlock,
  },
  deleteBlock: {
    method: 'DELETE',
    regex: /\/wp\/v2\/blocks\/(\d*)/g,
    run: deleteBlock,
  },
  optionBlocks: {
    method: 'OPTIONS',
    regex: /\/wp\/v2\/blocks($|[?].*)/g,
    run: optionBlocks,
  },
  getEmbed: {
    method: 'GET',
    regex: /\/oembed\/1\.0\/proxy\?(.*)/g,
    run: getEmbed,
  },
  postMedia: {
    method: 'POST',
    regex: /\/wp\/v2\/media/g,
    run: postMedia,
  },
  getMedia: {
    method: 'GET',
    regex: /\/wp\/v2\/media\/(\d*)/g,
    run: getMedia,
  },
  optionsMedia: {
    method: 'OPTIONS',
    regex: /\/wp\/v2\/media/g,
    run: optionsMedia,
  },
  getPage: {
    method: 'GET',
    regex: /\/wp\/v2\/pages\/(\d*)/g,
    run: getPage,
  },
  putPage: {
    method: 'PUT',
    regex: /\/wp\/v2\/pages\/(\d*)/g,
    run: putPage,
  },
  postPage: {
    method: 'POST',
    regex: /\/wp\/v2\/pages\/(\d*)/g,
    run: putPage,
  },
  getTaxonomies: {
    method: 'GET',
    regex: /\/wp\/v2\/taxonomies\?(.*)/g,
    run: getTaxonomies,
  },
  getThemes: {
    method: 'GET',
    regex: /\/wp\/v2\/themes/g,
    run: getThemes,
  },
  getTypeBlock: {
    method: 'GET',
    regex: /\/wp\/v2\/types\/wp_block/g,
    run: getTypeBlock,
  },
  getTypePage: {
    method: 'GET',
    regex: /\/wp\/v2\/types\/page/g,
    run: getTypePage,
  },
  getTypes: {
    method: 'GET',
    regex: /\/wp\/v2\/types\?(.*)/g,
    run: getTypes,
  },
};

async function optionBlocks() {
  return MockData.blocks;
}

async function getBlock(options, matches) {
  let id = matches[1];
  let response = await axios.get(`/dashboard/gutenberg/blocks/${id}`);
  return response.data;
}

async function getBlocks() {
  let response = await axios.get('/dashboard/gutenberg/blocks');
  return response.data;
}

async function postBlocks(options) {
  let response = await axios.post('/dashboard/gutenberg/blocks', options.data);
  return response.data;
}

async function putBlock(options, matches) {
  let id = matches[1];
  let response = await axios.put(`/dashboard/gutenberg/blocks/${id}`,
    options.data);
  return response.data;
}

async function deleteBlock(options, matches) {
  let id = matches[1];
  let response = await axios.delete(`/dashboard/gutenberg/blocks/${id}`);
  return response.data;
}

async function getEmbed(options, matches) {
  let response = await axios.get(`/dashboard/gutenberg/oembed?${matches[1]}`);
  return response.data;
}

async function postMedia(options) {
  let response = await axios.post(`/dashboard/gutenberg/media`, options.body);
  return response.data;
}

async function getMedia(options, matches) {
  let response = await axios.get(`/dashboard/gutenberg/media/${matches[1]}`);
  return response.data;
}

async function optionsMedia() {
  return MockData.media;
}

async function getPage() {
  let content = window.gutenbergContent
    ? window.gutenbergContent.raw || ''
    : '';

  return {
    ...MockData.page,
    content: {
      raw: content,
    },
  };
}

async function putPage(options) {
  return {
    ...MockData.page,
    content: {
      raw: options.data,
    },
  };
}

async function getTaxonomies() {
  return 'ok';
}

async function getThemes() {
  return MockData.themes;
}

async function getTypeBlock() {
  return MockData.types.block;
}

async function getTypePage() {
  return MockData.types.page;
}

async function getTypes() {
  return MockData.types;
}

class FetchError extends Error {
  constructor(object) {
    super(object.message);
    this.data = object;
  }
}

function matchPath(options) {
  let promise;

  Object.keys(requests).forEach((key) => {
    let request = requests[key];
    // Reset lastIndex so regex starts matching from the first character
    request.regex.lastIndex = 0;
    let matches = request.regex.exec(options.path);

    if ((options.method === request.method ||
        (!options.method && request.method === 'GET')) &&
      matches && matches.length > 0) {
      promise = request.run(options, matches);
    }
  });

  if (!promise) {
    promise = new Promise((resolve, reject) => {
      return reject(new FetchError({
        code: 'api_handler_not_found',
        message: 'API handler not found.',
        data: {
          path: options.path,
          options: options,
          status: 404,
        },
      }));
    }).catch(error => {
      console.warn(`${error.message} ${error.data.data.path}`, error.data);
    });
  }
  return promise;
}

export default function apiFetchHandler(options) {
  return matchPath(options);
}
