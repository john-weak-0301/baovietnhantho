import { useQuery } from 'react-query';

export const useQueryMenuGroup = (enabled) => {
  return useQuery(
    'menuGroup',
    () => axios.get('/dashboard/ajax/links').then(res => res.data?.data),
    {
      enabled,
      refetchOnWindowFocus: false,
    },
  );
};

export const useSearchLinks = ({ query, types }, isEnabled) => {
  const config = { params: { query, types } };

  return useQuery(
    ['searchLinks', query, types],
    () => axios.get('/dashboard/ajax/links/search', config)
      .then(res => res.data?.data),
    {
      enabled: isEnabled,
      refetchOnWindowFocus: false,
    },
  );
};
