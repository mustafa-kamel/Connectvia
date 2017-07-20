/*
 * respond as in respond to server call
 * respond program is designed to call other program using system()
 * respond is only designed for handling server calls
*/


#include <stdio.h>		//to use printf()
#include <string.h>		//to used strcmp()
#include <sstream>		//to use stringstream
#include <stdlib.h>     //to use system, NULL, EXIT_FAILURE

using namespace std; // for stringstream

int ret;
//light sid = 1
//temp sid = 3

int main(int argc, char *argv[]){
	if(argc != 4){
		printf("erroe in paragmeters.\nProgram must be called as follows: respond <sid> <state> <val>\n"); //state is to open or close
		return 1;
		//exit(1);
	}
	
	
		
	if(strcmp(argv[2],"0")==0){ //state
		printf("enable is 0\n");
		return 0;
	}
		
	
	
	//id [1] state[2] val[3]
	
	stringstream command;
	
	if(strcmp(argv[1],"3")== 0){ // for temp control
		printf("sid = 3\n");
		command<<"sudo ./collector_temp "<< argv[3];
		//ret = system(command.str().c_str());
		printf("system return = %d\n",ret);
	}
	else if(strcmp(argv[1],"1")== 1){ //for light control
		printf("sid = 1\n");
		command<<"sudo ./collector_light "<< argv[3];
		//ret = system(command.str().c_str());
		printf("sudo ./system return = %d\n",ret);
	}
	else{
		printf("undefined sensor\n");
	}
	
	return 0;
}
