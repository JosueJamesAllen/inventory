-- Migration: add purpose and expected_return_at to transactions
-- Run this if your transactions table was created before these columns existed.
-- Safe to run multiple times (uses IF NOT EXISTS pattern via ALTER IGNORE or column check).

USE inventory_db;

ALTER TABLE transactions
    ADD COLUMN IF NOT EXISTS purpose             TEXT        NULL AFTER notes,
    ADD COLUMN IF NOT EXISTS expected_return_at  DATETIME    NULL AFTER purpose;
