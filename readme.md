![Grafite Scaffold](https:://docs.grafite.ca/grafite_scaffold.png)

### In App Notification
```
$notification = new InAppNotification('this is a test');
$notification->isImportant();

auth()->user()->notify($notification);
```
With the Helper!
```
app_notification('This is words from inside the app!', true)
```

### Email based Notification

```
$notification = new StandardEmail('this is a test');

auth()->user()->notify($notification);
```
