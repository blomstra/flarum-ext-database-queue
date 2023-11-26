import app from 'flarum/admin/app';
import extendDashboardPage from './extendDashboardPage';

app.initializers.add('blomstra-database-queue', () => {
  app.extensionData.for('blomstra-database-queue');

  extendDashboardPage();
});
