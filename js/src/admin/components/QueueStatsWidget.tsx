import { NestedStringArray } from '@askvortsov/rich-icu-message-formatter';
import app from 'flarum/admin/app';
import DashboardWidget, { IDashboardWidgetAttrs } from 'flarum/admin/components/DashboardWidget';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import Button from 'flarum/common/components/Button';
import type Mithril from 'mithril';
import LinkButton from 'flarum/common/components/LinkButton';
import Tooltip from 'flarum/common/components/Tooltip';
import Switch from 'flarum/common/components/Switch';
import icon from 'flarum/common/helpers/icon';

export default class QueueStatsWidget extends DashboardWidget {
  loading = true;
  data: any = {};
  autoRefreshEnabled = false;
  autoRefreshInterval?: number;

  oncreate(vnode: Mithril.Vnode<IDashboardWidgetAttrs>) {
    super.oncreate(vnode);
    this.loadQueueStats();
  }

  onremove() {
    this.clearAutoRefresh();
  }

  async loadQueueStats() {
    this.loading = true;
    m.redraw();
    const data = await app.request({
      method: 'GET',
      url: app.forum.attribute('apiUrl') + '/database-queue/stats',
    });

    this.data = data;
    this.loading = false;
    m.redraw();
  }

  toggleAutoRefresh(enabled: boolean) {
    this.autoRefreshEnabled = enabled;
    if (enabled) {
      this.setAutoRefresh();
    } else {
      this.clearAutoRefresh();
    }
  }

  setAutoRefresh() {
    this.clearAutoRefresh();
    this.autoRefreshInterval = setInterval(() => this.loadQueueStats(), 5000) as unknown as number;
  }

  clearAutoRefresh() {
    if (this.autoRefreshInterval) {
      clearInterval(this.autoRefreshInterval as unknown as NodeJS.Timeout);
      this.autoRefreshInterval = undefined;
    }
  }

  className() {
    return 'QueueStatsWidget';
  }

  content() {
    return (
      <div className="QueueStatsWidget-container">
        <div className="QueueStatsWidget-header">
          <h4 className="QueueStatsWidget-title">{app.translator.trans('blomstra-database-queue.admin.stats.heading')}</h4>
          <div className="QueueStatsWidget-controls">
            <Tooltip text={app.translator.trans('blomstra-database-queue.admin.stats.refresh')}>
              <Button
                className="Button Button--icon"
                icon="fas fa-sync-alt"
                onclick={() => this.loadQueueStats()}
                disabled={this.loading || this.autoRefreshEnabled}
              />
            </Tooltip>
          </div>
        </div>
        <div className="QueueStatsWidget-grid">{this.renderStatsSection()}</div>
        <div className="QueueStatsWidget-footer">
          <Switch state={this.autoRefreshEnabled} onchange={this.toggleAutoRefresh.bind(this)} loading={this.loading}>
            {app.translator.trans('blomstra-database-queue.admin.stats.auto_refresh')}
          </Switch>
        </div>
      </div>
    );
  }

  renderStatsSection() {
    const { queue, status, pendingJobs, failedJobs } = this.data;

    return (
      <>
        {this.renderStatusIndicator(status)}
        {this.renderStat(app.translator.trans('blomstra-database-queue.admin.stats.data.queue-name'), queue)}
        {this.renderStat(app.translator.trans('blomstra-database-queue.admin.stats.data.pending-jobs'), pendingJobs)}
        {this.renderStat(app.translator.trans('blomstra-database-queue.admin.stats.data.failed-jobs'), failedJobs)}
      </>
    );
  }

  renderStat(label: NestedStringArray, value: string) {
    return (
      <div className="QueueStatsWidget-stat">
        <small>{label}</small>
        <p>{value || !this.loading ? value : <LoadingIndicator size="small" display="inline" />}</p>
      </div>
    );
  }

  renderStatusIndicator(status: string | null) {
    const iconClass = status === 'running' ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-danger';

    return (
      <div className="QueueStatsWidget-stat">
        <small>{app.translator.trans('blomstra-database-queue.admin.stats.data.status.label')}</small>
        <p>
          {icon(iconClass)} {status ? app.translator.trans(`blomstra-database-queue.admin.stats.data.status.${status}`) : ''}
        </p>
      </div>
    );
  }
}
