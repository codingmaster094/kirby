<?php

go(($page->parent() ?? site()->homePage())->url(), 301);
