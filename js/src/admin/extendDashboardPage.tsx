import { extend } from 'flarum/common/extend';
import type Mithril from 'mithril';

import DashboardPage from 'flarum/admin/components/DashboardPage';
import ItemList from 'flarum/common/utils/ItemList';
import QueueStatsWidget from './components/QueueStatsWidget';

export default function extendDashboardPage() {
  extend(DashboardPage.prototype, 'availableWidgets', function (widgets: ItemList<Mithril.Children>) {
    widgets.add('horizon-stats', <QueueStatsWidget />, 30);
  });
}
