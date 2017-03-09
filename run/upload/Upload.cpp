#include<iostream>
#include<fstream>
#include<string>
#include<cstdio>
#include<cstdlib>
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
	cout<<"database_path:   "<< result_database_path <<endl;
	
	//mysql init 
	MYSQL mysql;
	MYSQL_RES *result;
	MYSQL_ROW row;
	mysql_init(&mysql);
	mysql_real_connect(&mysql, "localhost", "root", "root", "test", 3306, NULL, 0);
	string sql = "UPDATE `db_file` SET `status` = 'success',`db_location` = '" + result_database_path + "' WHERE `database`= '" + db_Name + "';";
	string sql_fail = "UPDATE `db_file` SET `status` = 'fail',`db_location` = '" + result_database_path + "' WHERE `database`= '" + db_Name + "';";
//	string sql_query = sql + filename + "' , '" + filepath + "');";
	
		
	string run_scipt = "sh ./Upload.sh " + file_list_path + " " + pic_src_path + " " + pic_list_count + " " + result_database_path +"  > /home/saltedfish/log.log";
	int return_num=system(run_scipt.c_str());
	cout<< run_scipt<<endl;
//	pr_exit(return_num);
	cout <<"return :" <<return_num<<endl;


	if (return_num > 0) {
		cout<<"sql : "<<sql<<endl;
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
