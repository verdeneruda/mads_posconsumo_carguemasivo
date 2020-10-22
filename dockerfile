FROM recognizebv/symfony-docker:php7.1
ADD . /var/www/html
# Add your application build steps here, for example:
# RUN ./var/www/html/web/bin/...
RUN rm -rf /var/www/html/web/app_dev.php
RUN rm -rf /var/www/html/web/config.php