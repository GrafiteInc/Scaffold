Scaffold

### General Notification
```
$notification = new InAppNotification('this is a test');
$notification->isImportant();

auth()->user()->notify($notification);
```

###