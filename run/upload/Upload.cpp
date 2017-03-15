#include<iostream>
#include<fstream>
#include<string>
#include<cstdlib>
#include<cstdio>
#include<mysql/mysql.h>
using namespace std;
//./bin/DoIndex -i $file_list_path $pic_src_path $pic_list_count 250 $result_database_path GPU 0
int main(int strs, char** str) {
	// args
	string file_list_path = str[1];
	string pic_src_path = str[2];
	string pic_list_count = str[3];
	string result_database_path = str[4];
	string db_Name = str[5];
	cout<<db_Name<<endl;
	//mysql init 
	MYSQL mysql;
	MYSQL_RES *result;
	MYSQL_ROW row;
	mysql_init(&mysql);
	mysql_real_connect(&mysql, "localhost", "root", "root", "test", 3306, NULL, 0);
	string sql = "UPDATE `db_file` SET `status` = 'success',`db_location` = '" + result_database_path + "' WHERE `database`= '" + db_Name + "';";
	string sql_fail = "UPDATE `db_file` SET `status` = 'fail' WHERE `database`= '" + db_Name + "';";
//	string sql_query = sql + filename + "' , '" + filepath + "');";
	cout<<sql<<endl;	
	string sql_process = "UPDATE `db_file` SET `status` = 'process' WHERE `database`= '" + db_Name + "';";
	mysql_query(&mysql, sql_process.c_str());	
	string run_scipt = "sh /home/slh/pro/run/upload/Upload.sh " + file_list_path + " " + pic_src_path + "/ " + pic_list_count + " " + result_database_path;
	int return_num=system(run_scipt.c_str());
	cout <<run_scipt<<endl;
	cout<<return_num<<endl;
	if (return_num == 0 || return_num==256) {
		mysql_query(&mysql, sql.c_str());
	}
	else {
		mysql_query(&mysql, sql_fail.c_str());
	}
	mysql_close(&mysql);

	//执行平台功能
	//string run_scipt;
	//system(run_scipt.c_str());
	cout << "DONE";
	return 0;
}
