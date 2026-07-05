<?php

$conn = new PDO("sqlite:db.sqlite");

$conn->exec("ALTER TABLE cartas ADD COLUMN texto TEXT");