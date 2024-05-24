import { useMemo, useRef } from 'react';
import { DetailsList, Selection } from '@fluentui/react';

import { parsePathName } from './utils';

const columns = [
  {
    key: 'name',
    name: 'Title',
    fieldName: 'title',
    onRender: (item) => (
      <div className="external-links">
        <strong>{item.title}</strong><br />
        <a href={item.url} target="_blank">{parsePathName(item.url)}</a>
      </div>
    ),
  },
  {
    key: 'value',
    name: 'Type',
    fieldName: 'type',
    onRender: (item) => item.type?.toUpperCase(),
  },
];

export default function SearchLinkResults({
  data,
  onSelect = () => undefined,
}) {
  const items = useMemo(() => {
    return Object.values(data)
      .reduce((all, items) => [...all, ...items], []);
  }, [data]);

  const selection = useRef(new Selection({
    onSelectionChanged: () => onSelect(selection.current.getSelection()[0]),
  }));

  return (
    <DetailsList
      items={items}
      columns={columns}
      getKey={(item) => item.url}
      compact={true}
      isHeaderVisible={false}
      selection={selection.current}
      selectionMode="single"
    />
  );
}
