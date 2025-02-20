# Agricultural-Zone-woo-commerce-Plugin
A WooCommerce/Wordpress Plugin that adds agricultural zones to shipping addresses

Its is a simple plug in that adds agricultural hardiness zones to shipping woo commerce adresses and to the orders view of woocommerce

It's not complicated. To activate, a table needs to be created named hardiness_zones and data needs to be imported to that tabel from the hardinesszones CSV file included in the project. Then upload the plugin to the plugin directory and acivate it though the Wordpress plugins . 

I didnt include a script to create the database when the plugin in activated. I may add that at a later date.
hardiness_zones create table script:

CREATE TABLE `hardiness_zones` (
  `zip_code` varchar(8) NOT NULL,
  `zone` varchar(4),
  `city` varchar(27),
  `state` varchar(5),
  `latitude` varchar(8),
  `longitute` varchar(89)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
