## agar bina php artisan serve ke chalana ho to agar normaly na chale to sabse pahle check karo

sudo nano /etc/apache2/apache2.conf

<Directory /var/www/>
Options Indexes FollowSymLinks
AllowOverride All
Require all granted

``
</Directory>

```
sudo systemctl restart apache2
```

sudo a2enmod rewrite
sudo systemctl restart apache2

http://localhost/laravel12/public/
