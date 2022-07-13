<?php

try {
    System\FastApp::getInstance();
} catch (Exception $e) {
    die($e);
}