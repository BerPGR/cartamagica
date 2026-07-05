<?php

$conn = new PDO("sqlite:db.sqlite");

$conn->exec("CREATE TABLE cartas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    session_id TEXT NOT NULL,
    texto_carta TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'aguardando_pagamento' CHECK (status IN ('aguardando_pagamento', 'pago')),
    mp_preference_id TEXT,
    mp_payment_id TEXT,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);");