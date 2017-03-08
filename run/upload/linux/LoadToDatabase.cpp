#include<iostream>
#include<fstream>
#include<string>
#include<mysql/mysql.h>
using namespace std;

//#pragma comment(lib,"libmysql.lib")
int main(int strs, char** str) {
	string databasename = str[2];
	//mysql init 
	MYSQL mysql;
	MYSQL_RES *result;
	MYSQL_ROW row;
	mysql_init(&mysql);
	mysql_real_connect(&mysql, "localhost", "root", "root", "test", 3306, NULL, 0);
	
	string sql = "Insert into " + databasename + "(name,Location) values ('";

	ifstream infile(str[1], ios::in);
	string location;
	long long num = 0;
	while (infile>>location) {
		num++;
		int index=location.find_last_of('/');
		string filepath = location.substr(0, index);
		string filename = location.substr(index + 1);
		
		string sql_query = sql + filename + "' , '" + filepath + "');";
		mysql_query(&mysql, sql_query.c_str());
		//cout << filepath << "   " << filename << endl;
	}
	mysql_close(&mysql);
	
	//执行平台功能
	//string run_scipt;
	//system(run_scipt.c_str());
	cout << "SUCCESS "<<num;
	return 0;
}
