export const parsePathName = (url) => {
  if (!url || (typeof url === 'string' && url.startsWith('/'))) {
    return '';
  }

  try {
    return (new URL(url)).pathname;
  } catch (error) {
    return '/';
  }
};
