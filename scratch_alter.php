<?php
use Illuminate\Support\Facades\DB;
DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('staff', 'admin', 'super_admin', 'officer') NOT NULL DEFAULT 'staff'");
echo "Success\n";
