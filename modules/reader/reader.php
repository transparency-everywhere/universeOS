<?php header("Content-Type: application/rss+xml");
echo('<?xml version="1.0" encoding="ISO-8859-1"?>'); ?>
        <?php
        include("http://www.spiegel.de/schlagzeilen/index.rss ");
        ?>