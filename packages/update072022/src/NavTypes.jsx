import { useCallback, useEffect, useRef } from 'react';
import { Checkbox, Selection } from '@fluentui/react';

const parseIds = (item) => item.categories?.map(o => `${item.type}:${o.id}`);

const parseDataKeys = (data) => data.reduce(
  (arr, data) => [...arr, data.type, ...(parseIds(data) || [])],
  ['all_type'],
);

const NestedCategories = ({ item, selection }) => {
  if (!Array.isArray(item.categories)) {
    return null;
  }

  const key = (c) => `${item.type}:${c.id}`;

  const onChange = (category, checked) => {
    selection.setKeySelected('all_type', false);

    selection.setKeySelected(key(category), checked);
  };

  return (
    <ul className="list-unstyled pl-4">
      {item.categories.map((category) => (
        <li key={key(category)}>
          <Checkbox
            label={category.name}
            value={key(category)}
            checked={selection.isKeySelected(key(category))}
            onChange={(e, checked) => onChange(category, checked)}
          />
        </li>
      ))}
    </ul>
  );
};

export default function NavTypes({
  data,
  onChange = () => undefined,
}) {
  const selection = useRef(new Selection({
    items: parseDataKeys(data),
    getKey: (item) => item,
    onSelectionChanged: () => onChange(selection.current.getSelection()),
  }));

  const setGroupChecked = useCallback((item, isChecked) => {
    let ids = parseIds(item) || [];

    selection.current.setKeySelected('all_type', false);
    selection.current.setKeySelected(item.type, isChecked);

    for (let _id of ids) {
      selection.current.setKeySelected(_id, isChecked);
    }
  }, []);

  const isGroupIndeterminate = useCallback((item) => {
    if (!item.categories) {
      return false;
    }

    const ids = parseIds(item);
    const checked = selection.current.getSelection();

    return ids.some((key) => checked.includes(key)) &&
      !ids.every((key) => checked.includes(key));
  }, []);

  const onSelectAllChange = (e, isChecked) => {
    selection.current.setAllSelected(false);
    selection.current.setKeySelected('all_type', isChecked);
  };

  useEffect(() => {
    selection.current.setKeySelected('all_type', true);
  }, []);

  return (
    <ul className="list-unstyled">
      <li>
        <Checkbox
          value="all_type"
          label={<strong>Toàn bộ link</strong>}
          checked={selection.current.isKeySelected('all_type')}
          onChange={onSelectAllChange}
        />
      </li>

      {data.map((item) => (
        <li key={item.type}>
          <Checkbox
            value={item.type}
            label={<strong>{item.name}</strong>}
            checked={selection.current.isKeySelected(item.type)}
            onChange={(e, checked) => setGroupChecked(item, checked)}
            indeterminate={isGroupIndeterminate(item)}
          />

          {item.categories && (
            <NestedCategories
              item={item}
              selection={selection.current}
            />
          )}
        </li>
      ))}
    </ul>
  );
}
