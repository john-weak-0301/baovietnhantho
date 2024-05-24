import TableController from './controllers/table';
import ClipboardController from './controllers/clipboard';
import UploadScreenController from './controllers/upload-screen';

import FieldsTag from './controllers/field-tags';
import FieldSelectArea from './controllers/field-select-area';
import ComponentsMenu from './controllers/menu-screen';

// Register app controllers.
const {application} = window;

application.register('table', TableController);
application.register('clipboard', ClipboardController);
application.register('upload-screen', UploadScreenController);

application.register('fields--tag', FieldsTag);
application.register('field--select-area', FieldSelectArea);

application.register('components--menu', ComponentsMenu);
