/*
 * respond as in respond to server call
 * respond program is designed to call other program using system()
 * respond is only designed for handling server calls
*/

#include <iostream>
#include <stdio.h>		//to use printf()
#include <string.h>		//to used strcmp()
#include <sstream>		//to use stringstream
#include <stdlib.h>     //to use system, NULL, EXIT_FAILURE

using namespace std; // for stringstream

int ret;
float light_ref_percentage;
float light_ref_voltage;
//light sid = 1
//temp sid = 3

int main(int argc, char *argv[]){
	if(argc < 3 ){		//the program must be always is the same format: 1: respond , 2: sid , 3: state , 4: val so if it's less than 3 arguments then it was called the wrong way
		printf("erroe in paragmeters.\nProgram must be called as follows: respond <sid> <state> <<val>>\n"); //state is to open or close
		return 1;
		//exit(1);
	}
	
	if(strcmp(argv[2],"0")==0 && argc == 4){		//argv[2] is the state so if it's 0 then light or temperature is disabled then no need to forward it to arduino
		printf("enable is 0\n");
		cout << "dropping//ignoring command\n";
		return 0;
	}
	
	stringstream command;
	
	//server program will call this program like thins: sudo ./server <sid> <state> <value> so map every sensor to the program that we made that control this sensor
	if(strcmp(argv[1],"3")== 0){ // for temp1 control, for hall
		printf("sid = 3 (in hall -living-)\n");
		command<<"sudo ./collector_temp hall "<< argv[3];
		ret = system(command.str().c_str());
		printf("system call return = %d\n",ret);
	}
	else if(strcmp(argv[1],"1")== 0){ //for light1 control, for hall
		printf("sid = 1 (in hall -living-)\n");
		light_ref_percentage = atof(argv[2]);
		cout<<"arg 2 "<<argv[2]<<endl;
		light_ref_voltage = light_ref_percentage * (5.0/100);
		cout<<"light ref in volt = "<<light_ref_voltage<<endl;
		command<<"sudo ./collector_light hall "<< light_ref_voltage;
		ret = system(command.str().c_str());
		printf("system call return = %d\n",ret);
	}
	else if(strcmp(argv[1],"6")== 0){ //tv1, hall
		printf("sid = 6 (in hall -living-)\n");
		command<<"sudo ./write_tv "<< argv[2];
		ret = system(command.str().c_str());
		printf("system call return = %d\n",ret);
	}
	else if(strcmp(argv[1],"16")== 0){  //light4, in room1 -bedroom1-
		printf("sid = 7 (in room1 -bedroom1-)\n");
		command<<"sudo ./collector_light room1 "<< argv[3];
		ret = system(command.str().c_str());
		printf("system call return = %d\n",ret);
	}
	else if(strcmp(argv[1],"15")== 0){  //garage door, hall -garage-
		printf("sid = 15 (in hall -garage-)\n");
		command<<"sudo ./write_door "<< argv[3];
		ret = system(command.str().c_str());
		printf("system call return = %d\n",ret);
	}
	else if(strcmp(argv[1],"18")== 0){  //for temp_4, room1 -bedroom1-
		printf("sid = 18 (in room1 -bedroom1-)\n");
		command<<"sudo ./collector_temp room1 "<< argv[3];
		ret = system(command.str().c_str());
		printf("system call return = %d\n",ret);
	}
	else{
		printf("undefined sensor\n");
	}
	
	return 0;
}
