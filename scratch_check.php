<?php
use Illuminate\Support\Facades\DB;
dump(DB::select("SHOW COLUMNS FROM users LIKE 'role'"));
