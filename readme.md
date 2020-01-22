![Grafite Scaffold](https://docs.grafite.ca/img/grafite_scaffold.png)

### In App Notification
```
$notification = new InAppNotification('this is a test');
$notification->isImportant();

auth()->user()->notify($notification);
```
With the Helper!
```
app_notify('This is words from inside the app!', true)
email_notify('subject', 'This is words from inside the app!')
```

### Email based Notification

```
$notification = new StandardEmail('this is a test');

auth()->user()->notify($notification);
```
