import app from 'flarum/admin/app';
import extendDashboardPage from './extendDashboardPage';

app.initializers.add('blomstra-database-queue', () => {
  app.extensionData
    .for('blomstra-database-queue')
    .registerSetting({
      setting: 'blomstra-database-queue.retries',
      type: 'number',
      label: app.translator.trans('blomstra-database-queue.admin.settings.retries_label'),
      help: app.translator.trans('blomstra-database-queue.admin.settings.retries_help'),
      min: 1,
      max: 5,
    })
    .registerSetting({
      setting: 'blomstra-database-queue.timeout',
      type: 'number',
      label: app.translator.trans('blomstra-database-queue.admin.settings.timeout_label'),
      help: app.translator.trans('blomstra-database-queue.admin.settings.timeout_help'),
      min: 10,
    })
    .registerSetting({
      setting: 'blomstra-database-queue.rest',
      type: 'number',
      label: app.translator.trans('blomstra-database-queue.admin.settings.rest_label'),
      help: app.translator.trans('blomstra-database-queue.admin.settings.rest_help'),
      min: 0,
    })
    .registerSetting({
      setting: 'blomstra-database-queue.memory',
      type: 'number',
      label: app.translator.trans('blomstra-database-queue.admin.settings.memory_label'),
      help: app.translator.trans('blomstra-database-queue.admin.settings.memory_help'),
      min: 64,
    })
    .registerSetting({
      setting: 'blomstra-database-queue.backoff',
      type: 'number',
      label: app.translator.trans('blomstra-database-queue.admin.settings.backoff_label'),
      help: app.translator.trans('blomstra-database-queue.admin.settings.backoff_help'),
      min: 0,
    });

  extendDashboardPage();
});
