-- Migration: Add soft delete support to projects table
-- This adds a deleted_at column to track soft-deleted records

ALTER TABLE `projects` ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL AFTER `updated_at`;

-- Create an index on deleted_at for faster queries
ALTER TABLE `projects` ADD INDEX `idx_deleted_at` (`deleted_at`);
