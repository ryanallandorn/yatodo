#!/bin/bash

# Get the directory of the script
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Load environment variables from .env file
set -o allexport
source /home/dorn/Dev/yatodo/.env
set -o allexport

# Configurations
BACKUP_DIR="$SCRIPT_DIR"  # Use the script's directory as the base
HOURLY_DIR="$BACKUP_DIR/hourly"
DAILY_DIR="$BACKUP_DIR/daily"
LOG_FILE="$BACKUP_DIR/backup.log"  # Log file to track backups
DB_NAME="$DB_DATABASE"  # Extracted from .env
DB_USER="$DB_USERNAME"  # Extracted from .env
DB_PASSWORD="$DB_PASSWORD"  # Extracted from .env
RETENTION_HOURLY=12   # Number of hourly backups to keep

# Ensure the backup directories and log file exist
mkdir -p "$HOURLY_DIR"
mkdir -p "$DAILY_DIR"
touch "$LOG_FILE"

# Date Formats
TIMESTAMP=$(date +%Y-%m-%d_%H-%M-%S)
DAILY_TIMESTAMP=$(date +%Y-%m-%d)

# Perform Backup (with password)
BACKUP_FILE="$HOURLY_DIR/db_backup_$TIMESTAMP.sql"
PGPASSWORD=$DB_PASSWORD pg_dump -U $DB_USER -h $DB_HOST -p $DB_PORT $DB_NAME > "$BACKUP_FILE"

# Log the creation of the hourly backup
echo "[$(date +%Y-%m-%d\ %H:%M:%S)] Created hourly backup: $BACKUP_FILE" >> "$LOG_FILE"

# Move the last hourly backup to the daily directory (only once per day)
DAILY_BACKUP="$DAILY_DIR/db_backup_$DAILY_TIMESTAMP.sql"
if [ ! -f "$DAILY_BACKUP" ]; then
    cp "$BACKUP_FILE" "$DAILY_BACKUP"
    echo "[$(date +%Y-%m-%d\ %H:%M:%S)] Saved daily backup: $DAILY_BACKUP" >> "$LOG_FILE"
fi

# Delete old hourly backups (keeping only the last 12)
cd "$HOURLY_DIR"
ls -1tr | head -n -$RETENTION_HOURLY | xargs -d '\n' rm -f --
echo "[$(date +%Y-%m-%d\ %H:%M:%S)] Cleaned up old hourly backups, keeping the last $RETENTION_HOURLY backups." >> "$LOG_FILE"

