#  Deploy WordPress on AWS EC2 Ubuntu  

A step-by-step walkthrough for launching an EC2 instance, securing it with an SSH key, installing a full LAMP stack, dropping WordPress on top, and enabling HTTPS with Let’s Encrypt.  
Works for **IP-only access (`3.107.184.106`)** *or* a real domain <http://grocerycloudcart.online/>

Youtube Link - <https://youtu.be/YM9Fr3OltbA>

---

## ⚙️ Prerequisites

| Item | Notes |
|------|-------|
| AWS account | Free tier |
| Terminal | Ubuntu |
| Open ports | TCP **22, 80, 443** in the instance’s security group |
| PEM key | **`ict.pem`** |

---

## Launch an EC2 Instance

1. Sign in to the **AWS Console** → <https://aws.amazon.com/>.
2. Search for **EC2** and open the service.
3. Click **Launch instance**.
4. **Name** the server.  
5. Choose an **Ubuntu 24.04** AMI.  
6. Click **Create new key pair** →  
   * **Type:** RSA  
   * **Format:** `.pem`
7. In **Network settings** tick  
   * **Allow HTTPS traffic from the internet**  
   * **Allow HTTP traffic from the internet**
8. Press **Launch instance** and wait until the button **Connect** appears.

> **Tip:** If “Connect to instance” never shows up, repeat the steps carefully—most failures are missed network-settings boxes or key‐pair errors.

---

## SSH In & Update the Box




### — Run the following commands in order

```bash
# ❶ Lock down the key file
chmod 400 ict.pem

# ❷ SSH into the instance (use the instance’s public IPv4)
ssh -i ict.pem ubuntu@3.107.184.106

# ❸–❺ Adjust web-root permissions (developer-friendly defaults)
sudo chgrp -R www-data /var/www/html/
sudo gpasswd -a ubuntu www-data
sudo chmod -R 777 /var/www/html/

# ❻ Edit the default page
nano /var/www/html/index.html
```

##  Install WordPress on Your Instance

After Apache 2 is installed and you’ve SSH-ed into the box with its public IP, it’s time to lay down a full **LAMP** stack and prepare the database for WordPress.

---

### Install the LAMP Stack

Run the commands **exactly in order**:

```bash
# 1) Apache
sudo apt install -y apache2
sudo systemctl enable --now apache2

# 2) MariaDB
sudo apt install -y mariadb-server mariadb-client
sudo mysql_secure_installation      # answer the prompts interactively

# 3) PHP 8.3 + common WordPress extensions
sudo apt install -y php8.3 \
  php8.3-{bz2,curl,gd,intl,mbstring,mysql,xml,zip,opcache}
```

### Create the WordPress Database & User
```bash
sudo mysql -u root -p

Paste the block below into the MariaDB prompt:
CREATE DATABASE wordpress
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER 'wpuser'@'localhost'
  IDENTIFIED BY 'Str0ng!Passw0rd';

GRANT ALL PRIVILEGES ON wordpress.* TO 'wpuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

###  Download & Unpack WordPress

Run the commands **in order** to pull WordPress and place it under `/var/www/grocerycloudcart`:

```bash
# 1) Move to a temp workspace
cd /tmp

# 2) Download the latest WordPress tarball
curl -O https://wordpress.org/latest.tar.gz

# 3) Unpack it
tar xzvf latest.tar.gz

# 4) Create the target directory (change the name if you prefer)
sudo mkdir -p /var/www/grocerycloudcart

# 5) Sync the WordPress files into that directory
sudo rsync -aP wordpress/ /var/www/grocerycloudcart/

# 6) Give Apache (www-data) ownership
sudo chown -R www-data:www-data /var/www/grocerycloudcart
```
### Install the Apache Virtual Host

1. **Create the vhost file**

   ```bash
   sudo nano /etc/apache2/sites-available/grocerycloudcart.conf
Paste the block below and save it:

<VirtualHost *:80>
    ServerName  grocerycloudcart.online
    ServerAlias www.grocerycloudcart.online
    ServerAdmin webmaster@grocerycloudcart.online

    DocumentRoot /var/www/grocerycloudcart
    <Directory /var/www/grocerycloudcart>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog  ${APACHE_LOG_DIR}/grocerycloudcart_error.log
    CustomLog ${APACHE_LOG_DIR}/grocerycloudcart_access.log combined
</VirtualHost>

Enable the site and required modules, then reload Apache using the following commands:

```bash
sudo a2enmod rewrite
sudo a2ensite grocerycloudcart
sudo systemctl reload apache2
```

### Configure WordPress

Run the commands **in order**:

```bash
cd /var/www/grocerycloudcart            # 1) enter the site root
cp wp-config-sample.php wp-config.php   # 2) make an editable copy
nano wp-config.php                      # 3) open it in nano (or vim)
```

Edit the DB settings so they match what you created earlier:
define( 'DB_NAME',     'wordpress' );
define( 'DB_USER',     'wpuser' );
define( 'DB_PASSWORD', 'Str0ng!Passw0rd' );
define( 'DB_HOST',     'localhost' );

Generate fresh salts (security keys):

```bash
curl -s https://api.wordpress.org/secret-key/1.1/salt/
```
Back in nano

Highlight the old eight placeholder lines

Paste the new eight lines to replace them

Save (Ctrl+O, Enter) and exit (Ctrl+X)
Then:
```bash
sudo systemctl reload apache2
```

###  Install **Certbot** and its Apache plugin

Run the following commands **one-by-one** in your ubuntu terminal:

```bash
# 1. Update package lists
sudo apt update

# 2. Install Certbot and the Apache plugin
sudo apt install -y certbot python3-certbot-apache

# 3. Obtain & install the certificate (replace with *your* domain names)
sudo certbot --apache \
  -d grocerycloudcart.online \
  -d www.grocerycloudcart.online
