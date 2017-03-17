#include<iostream>
#include<fstream>
#include<string>
#include<mysql/mysql.h>
using namespace std;

//#pragma comment(lib,"libmysql.lib")
int main(int strs, char** str) {
	string databasename = str[2];
	string filePath=str[1];
	string fileout=filePath+"_out";
	//mysql init 
	MYSQL mysql;
	MYSQL_RES *result;
	MYSQL_ROW row;
	mysql_init(&mysql);
	mysql_real_connect(&mysql, "localhost", "root", "root", "test", 3306, NULL, 0);
	
	string sql = "Insert into " + databasename + "(name,Location) values ('";
	ifstream infile(str[1], ios::in);
	ofstream outfile(fileout.c_str(),ios::out);
	string location;
	long long num = 0;
	string filepath;
	//first is filepath;
	infile>>location;
	filepath=location;
	while (infile>>location) {
		num++;
		string filename = location;
		outfile<<filename<< " "<<"0"<<endl;
		string sql_query = sql + filename + ".jpg' , '" + filepath + "');";
//		cout<<sql_query<<endl;
		mysql_query(&mysql, sql_query.c_str());
		//cout << filepath << "   " << filename << endl;
	}
	mysql_close(&mysql);
	
	//执行平台功能
	//string run_scipt;
	//system(run_scipt.c_str());
	cout << "SUCCESS "<<num<< " "<<fileout<<" "<<filepath;
	return 0;
}
