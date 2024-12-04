![](https://extiverse.com/extension/blomstra/database-queue/open-graph-image)

This extension allows you to easily run a queue, using your database and by configuring one cron(job).

> **Disclaimer**: this extension is provided "as is" by Blomstra. Only community support is provided.

## Background and when you need this

By default, Flarum runs without a (background) queue. This means that all tasks of Flarum are processed during the request of the user.

A great example of this is email sending. When using flarum/subscriptions, fof/follow-tags, ianm/follow-users or any other extension that allows subscribing to things, email sent out to notify users. For smaller communities this is not a big deal, but at some point you'll notice your requests (posting for instance) may start to slow down. All of these emails are dispatched after the post is stored to the database.

To resolve this increasing burden, you can run a Queue. A queue runs on your server, it does not interact with the user and their requests. A user request, however, can dispatch tasks to the queue. This extension provides the easiest implementation of a queue and works on shared hosting environments as well.

## Install

```bash
composer require blomstra/database-queue:"*"
```

Enable the extension inside the admin area. If you already have the Flarum scheduler setup, there's nothing more to do. Otherwise, see below:

### Set up

Go into your hosting control panel and set up the following task to run every minute:

```bash
php flarum schedule:run
```

Or in cron language:

```bash
* * * * * cd /path/to/flarum && php flarum schedule:run
```

### FAQ

*What is the difference with blomstra/flarum-redis?*
The redis package (it's officially not an extension) is meant for larger communities. Redis offers the ability to scale the number of workers up however you need. Running a queue worker which pulls jobs from the database can also negatively impact your community performance when processing a lot of notifications and other queue tasks.

---

- Blomstra provides managed Flarum hosting.
- https://blomstra.net
