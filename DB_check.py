import pymysql

# 資料庫連線設定
db_config = {
    'host': 'localhost',
    'user': 'root',         # 你的資料庫帳號
    'password': 'nsysuee1!',         # 你的資料庫密碼
    'database': 'laravel_sso',
    'charset': 'utf8mb4',
    'cursorclass': pymysql.cursors.DictCursor
}

def scan_db():
    try:
        connection = pymysql.connect(**db_config)
        with connection.cursor() as cursor:
            # 1. 取得所有資料表
            cursor.execute("SHOW TABLES")
            tables = cursor.fetchall()
            db_name = db_config['database']
            table_key = f"Tables_in_{db_name}"

            print(f"=== 資料庫 {db_name} 結構掃描 ===\n")

            for table in tables:
                t_name = table[table_key]
                print(f"【資料表: {t_name}】")

                # 2. 取得欄位資訊
                cursor.execute(f"DESCRIBE `{t_name}`")
                columns = cursor.fetchall()
                for col in columns:
                    print(f"  - 欄位: {col['Field']:<15} | 類型: {col['Type']:<10} | Null: {col['Null']}")

                # 3. 取得外鍵關聯 (關鍵！)
                cursor.execute(f"""
                    SELECT COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = '{db_name}' 
                    AND TABLE_NAME = '{t_name}' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                """)
                fks = cursor.fetchall()
                if fks:
                    print("  * 外鍵關聯:")
                    for fk in fks:
                        print(f"    [!] {fk['COLUMN_NAME']} -> {fk['REFERENCED_TABLE_NAME']}({fk['REFERENCED_COLUMN_NAME']})")
                print("-" * 50)

    except Exception as e:
        print(f"發生錯誤: {e}")
    finally:
        if 'connection' in locals():
            connection.close()

if __name__ == "__main__":
    scan_db()