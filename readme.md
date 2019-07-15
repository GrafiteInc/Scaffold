Scaffold

### In App Notification
```
$notification = new InAppNotification('this is a test');
$notification->isImportant();

auth()->user()->notify($notification);
```

### Email based Notification

```
$notification = new StandardEmail('this is a test');

auth()->user()->notify($notification);
```

