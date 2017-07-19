#include "index.h"
#include "cstring"
#include "boost/timer.hpp"
#include <sys/time.h>
#include <opencv2/core/core.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <opencv2/imgproc/imgproc.hpp>
//¼ÇÂ¼Ò»¶¨Î»ÊýµÄÌØÕ÷
int table[( 1<< BYTE_INDEX )];
unsigned short record[TOTALBYTESIZE / BYTE_INDEX];
std::map<int, int> labelList;
////ÅÅÐòº¯Êý
void swap(SortTable *a, SortTable *b)
{
	int tmp;
	tmp = a->sum;
	a->sum = b->sum;
	b->sum = tmp;
	int trans;
	trans = a->info;
	a->info = b->info;
	b->info = trans;
}
int partition(SortTable arr[], int left, int right, int pivotIndex){
	int storeIndex = left;
	int pivotValue = arr[pivotIndex].sum;
	int i;

	swap(&arr[pivotIndex], &arr[right]);

	for (i = left; i < right; i++)
	{
		if (arr[i].sum <= pivotValue)
		{
			swap(&arr[i], &arr[storeIndex]);
			storeIndex++;
		}
	}
	swap(&arr[storeIndex], &arr[right]);
	return storeIndex;
}
int findKMax(SortTable arr[], int left, int right, int k){
	int nRet;
	int pivotIndex = left;

	nRet = partition(arr, left, right, pivotIndex);
	if (nRet < k)
	{
		return findKMax(arr, nRet + 1, right, k);
	}
	else if (nRet > k)
	{
		return findKMax(arr, left, nRet - 1, k);
	}
	return nRet;
}
///

/*
	³õÊ¼»¯
	¹¦ÄÜ´ý¶¨
*/
void InitIndex(void* p, char* filename, Info_String* in_str,int count){
//
	feature* temp = (feature*)p;
	int i = 0;
	DataSet* s = new DataSet[count];
	while (i<count){
		for (int j = 0; j < TOTALBYTESIZE / BYTE_INDEX; j++){
			unsigned int y = 0;
			char x;
			for (int k = 0; k < BYTE_INDEX; k++){
				x = filename[i*TOTALBYTESIZE + j* BYTE_INDEX+k];
				if (x == 0) y = y << 1;
				else y = (y << 1) | 1;
			}
			s[i].data[j] = y;
		}
		i++;
	}
	temp->setDataSet(s,count);
	temp->setInfo(in_str, count);
}

int* doHandle(char * dat){
	int * data = new int[TOTALBYTESIZE / BYTE_INDEX];
	for (int j = 0; j < TOTALBYTESIZE / BYTE_INDEX; j++){
		unsigned int y = 0;
		char x;
		for (int k = 0; k < BYTE_INDEX; k++){
			if (dat[j*BYTE_INDEX+k] == 0) y = y << 1;
			else y = (y << 1) | 1;
		}
		data[j] = y;
	}
	return data;
}
/*
	int size: ¿ª¶à´óµÄÊý×é
	·µ»ØÖµ
	void*£º ·µ»ØË÷ÒýÖµ
	
*/
void* CreateIndex(int size){
	feature* temp = new feature;
	return (void*)temp;
}
void CreateTable(const char * filename,int bits){
	std::ifstream ifstream_in(filename, std::ios::in);
	int x, y;
	for (int i = 0; i < (1 << bits); i++){
		ifstream_in >> x >> y;
		table[x]=y;
	}
	ifstream_in.clear();
	ifstream_in.close();
}
/*
	Ôö¼ÓÊý¾Ý¼ÇÂ¼
*/
bool AddToIndex(void*p,int* data,const char* in){
	feature* temp = (feature*)p;
	int total = temp->getTotalSize();
	int curNum = temp->getCount();
	if (total <curNum+1){
		DataSet* ne = new DataSet[curNum * 2];
		Info_String* info_ne = new Info_String[curNum * 2];
		memcpy(ne, temp->getDataSet(), sizeof(DataSet)*curNum);
		memcpy(info_ne, temp->getInfoSet(), sizeof(Info_String)*curNum);
		memcpy(ne[curNum].data,  data,sizeof(int[64]));
		strcpy(info_ne[curNum].info, in);
		temp->deleteData();
		temp->setDataSet(ne, curNum+1);
		temp->setInfo(info_ne, curNum+1);
		temp->setTotalSize(2 * curNum);
	}
	else{
		memcpy(&(temp->getDataSet()[curNum]), data, sizeof(int[64]));
		memcpy(&(temp->getInfoSet()[curNum]),in,  sizeof(Info_String));
		temp->setCount(curNum + 1);
	}
	return true;
}

/*
	´ÓÍâ²àµ¼ÈëÊý¾Ý
	*p£º Ô­ÓÐµÄÖ¸Õë
	filename: ÎÄ¼þÃû
	count: ¶ÁÈëµÄÊý¾ÝÁ¿
*/
bool LoadIndex(void* p, const char* filename,const char* info_file, int count){
	FILE* in = fopen(filename, "rb");
	FILE* in_info = fopen(info_file, "rb");
	feature* temp = (feature*)p;
	DataSet* da = new DataSet[count];
	Info_String* inst = new Info_String[count];

	fread(da, sizeof(DataSet) , count, in);
	fread(inst, sizeof(Info_String), count, in_info);

	fclose(in);
	temp->setInfo(inst, count);
	temp->setDataSet(da, count);
	return true;
}

/*
	ÊÍ·ÅÄÚ´æ¿Õ¼ä
*/
bool DeleteIndex(void* p){
	delete[] p;
	return true;
}

/*
	count: indexµÄ¸öÊý
	filename: ÎÄ¼þÃû
	mode: ÎÄ¼þÉú³É·½Ê½  w:Ð´Èë a:¸½¼Ó
*/
bool ArchiveIndex(void* p, const char* filename, const char* info_file,int count, char mode){
	feature* temp = (feature*)p;
	FILE* os, *os_info;
	if (mode == 'a'){
		os = fopen(filename, "ab");
		os_info = fopen(info_file, "ab");
		
	}
	else{
		os = fopen(filename, "wb");
		os_info = fopen(info_file, "wb");
	}
	fwrite(temp->getInfoSet(), sizeof(Info_String),count , os_info);
	fwrite(temp->getDataSet(), sizeof(DataSet), count, os);
	fclose(os);
	fclose(os_info);
	return true;
}

/*
	¼ìË÷º¯Êý
*/
int retrival(int* input, DataSet* get_t,Info_String* get_info, int total,string& result,int bits,int LIMIT,SortTable* sorttable){
	int calc = 0;
	int i = 0, indexLine = 0;
	int sum = 0;
	
	std::cout<<"start"<<std::endl;
	while (calc < total){
		sum = 0;
		memset(record, 0, sizeof(record));
		for (i = 0; i < TOTALBYTESIZE / bits; i++){
			record[i] = input[i] ^get_t[calc].data[i];
			sum += table[record[i]];
			if (sum > LIMIT){
				break;
			}
		}
		if (sum < LIMIT){
			sorttable[indexLine].sum = sum;
			sorttable[indexLine++].info = calc;
			calc ++;
		}
		else{
			calc++;
		}

	}
	std::cout<<"done "<<indexLine<<std::endl;
	int num_find=300<indexLine?300:indexLine;
	std::cout<<"num_find "<<num_find<<std::endl;
	findKMax(sorttable, 0, indexLine - 1,num_find);
	std::sort(sorttable, sorttable + num_find-1);
	result = get_info[sorttable[0].info].info;
	cout<<"done sort"<<endl;
	return num_find;
}
int retrival2(int* input, void* p, string& result, int bits,int LIMIT){
	timeval startTime,endTime;
	feature* temp = (feature*)p;
	int calc = 0;
	int* record = new int[TOTALBYTESIZE / bits];
	int i = 0, indexLine = 0;
	int sum = 0;
	int total = temp->getCount();
	DataSet* get_t=temp->getDataSet();
	Info_String* get_info=temp->getInfoSet();
	SortTable* sorttable=new SortTable[total];
	std::cout<<"start"<<std::endl;
	gettimeofday(&startTime,NULL);  
	while (calc < total){
		sum = 0;
		memset(record, 0, sizeof(record));
		for (i = 0; i < TOTALBYTESIZE / bits; i++){
			record[i] = input[i] ^get_t[calc].data[i];
			sum += table[record[i]];
			if (sum > LIMIT){
				break;
			}
		}
		if (sum < LIMIT){
			sorttable[indexLine].sum = sum;
//			sorttable[indexLine++].info =calc;
			calc ++;
		}
		else{
			calc++;
		}

	}
	findKMax(sorttable, 0, indexLine - 1, 1);
	gettimeofday(&endTime,NULL);  
	float Timeuse;  
	Timeuse = 1000000*(endTime.tv_sec - startTime.tv_sec) + (endTime.tv_usec - startTime.tv_usec); 
	Timeuse /= 1000000;  
	std::cout<<"time use    "<<Timeuse<<std::endl;
	//result = sorttable[0].info.c_str();
	return sorttable[0].sum;
}
void retrival_thread(int* input, DataSet* get_t,int begin,int total, int bits, int LIMIT,
	SortTable* sorttable,int* line_record){
	int calc = 0;
	int i = 0, indexLine = 0;//从1开始的
	int sum = 0;
	cout<<"total"<<total<<endl;
	unsigned short temp_record[TOTALBYTESIZE / BYTE_INDEX];
	while (calc < total){
		sum = 0;
		memset(temp_record, 0, sizeof(temp_record));
		for (i = 0; i < TOTALBYTESIZE / bits; i++){
			temp_record[i] = input[i] ^ get_t[calc].data[i];
			sum += table[temp_record[i]];
			if (sum > LIMIT){
				break;
			}
		}
		if (sum < LIMIT){
			sorttable[indexLine].sum = sum;
			sorttable[indexLine++].info = calc+begin;
			calc++;
		}
		else{
			calc++;
		}

	}
	cout<<"index"<<indexLine<<endl;
	line_record[0] = indexLine;
}
