import { useEffect, useState } from 'react';
import {
  Dialog,
  DialogFooter,
  SearchBox,
  PrimaryButton,
  DefaultButton,
} from '@fluentui/react';
import { useBoolean } from '@fluentui/react-hooks';

import NavTypes from './NavTypes';
import SearchLinkResults from './SearchLinkResults';
import { useQueryMenuGroup, useSearchLinks } from './useRequest';

export default function LinksPicker({ api }) {
  const [isOpen, { setTrue: openModal, toggle }] = useBoolean(false);

  const [query, setQuery] = useState();
  const [types, setTypes] = useState(['all_type']);
  const [selectedLink, setSelectedLink] = useState(['all_type']);

  const { data, isSuccess, isLoading } = useQueryMenuGroup(isOpen);
  const results = useSearchLinks({ query, types }, isSuccess);

  useEffect(() => setSelectedLink(undefined), [types, query]);

  useEffect(() => {
    api._openModalCallback = () => openModal();
  }, []);

  const onPickLink = () => {
    api.flush(selectedLink);

    toggle();
  };

  return (
    <Dialog
      title="Chọn Link"
      hidden={!isOpen}
      onDismiss={toggle}
      modalProps={{ isBlocking: isLoading }}
      dialogContentProps={{ showCloseButton: true }}
      maxWidth={980}
      minWidth={720}
    >
      {(data && isSuccess) && (
        <>
          <div className="LinksPicker">
            <div className="LinksPicker__side">
              <NavTypes
                data={data}
                onChange={setTypes}
              />
            </div>

            <div className="LinksPicker__main">
              <div className="LinksPicker__search">
                <SearchBox
                  placeholder="Tìm kiếm link"
                  defaultValue="asdasd"
                  onSearch={(value) => setQuery(value)}
                />

                {query && (
                  <p className="pt-2 pb-0 m-0">
                    Kết quả tìm kiếm cho: <i>{query}</i>
                  </p>
                )}
              </div>

              <div className="LinksPicker__results">
                {results.isLoading && (
                  <p className="text-center pt-4 pb-4">Loading...</p>
                )}

                {(!results.isLoading &&
                  (!results.data || results.data?.length === 0)) && (
                  <p className="text-center pt-4 pb-4">không tìm thấy kết quả phù hợp</p>
                )}

                {(!results.isLoading && results.data) && (
                  <SearchLinkResults
                    data={results.data}
                    onSelect={setSelectedLink}
                  />
                )}
              </div>
            </div>
          </div>

          <DialogFooter>
            <DefaultButton onClick={toggle} text="Đóng" />
            <PrimaryButton onClick={onPickLink} text="Chọn" disabled={!selectedLink} />
          </DialogFooter>
        </>
      )}
    </Dialog>
  );
}
