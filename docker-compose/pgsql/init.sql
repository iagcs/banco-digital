SELECT 'CREATE DATABASE picpay'
    WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'picpay')
