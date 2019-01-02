<?php

Authentication::getInstance()->logout();
header("Location:" . BASE_URL);
