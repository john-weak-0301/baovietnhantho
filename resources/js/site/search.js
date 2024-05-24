import autocomplete from 'autocomplete.js'

const options = {
  hint: false,
  debug: true,
  openOnFocus: true,
  minLength: 0,
  cssClasses: {
    root: 'search-autocomplete',
  },
};

const template = function(data) {
  return `<a href="${data.link}">${data.title}</a>`;
};

const datasets = [
  {
    source,
    cache: true,
    debounce: 350,
    displayKey: 'title',
    templates: {
      suggestion(suggestion) {
        return template(suggestion)
      },
    },
  },
];

function source(query, callback) {
  if (!query) {
    return;
  }

  axios.get('/json/services.json', {
    params: {title: query},
  }).then(response => {
    callback(response.data.data);
  })
}

function initializeAutocomplete() {
  const input = document.getElementById('autocomplete-services');
  const wrapper = $(input).closest('.form-search');

  $(input).on('focus', () => wrapper.addClass('focused')).on('blur', () => wrapper.removeClass('focused'));

  autocomplete(input, options, datasets).on('autocomplete:selected', function(event, suggestion, dataset, context) {
    if (context.selectionMethod === 'click') {
      return;
    }

    window.location.assign(suggestion.link);
  });
}

export default initializeAutocomplete
