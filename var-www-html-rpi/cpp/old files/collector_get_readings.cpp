#include <unistd.h>
#include <stdio.h>
#include <RF24/RF24.h>
#include <iostream>			//to use << and using std
#include <ctime>			//to get the time now
#include <fstream>
#include <string.h>
#include <sstream>			//to use string stream
#include <stdlib.h>     	//to use system, NULL, EXIT_FAILURE

#include <sys/stat.h>   //for mkfifo()

using namespace std;

/*
//fifo creation
int fifo_state;
FILE *fp;
char fifo_name[] = "readings_file";
char read_buffer[40];
char temp[40];


//----------------
 */

char send[40] = "readDataSLV1";
int current_time;
bool timeout;
bool ret;
time_t time_now = time(0);

//SLV2 variables 
float lightVoltage;	//the voltage of the reading of the photo resistor
float refVoltage;	//the reference of the reference of the light in voltage
float gasVoltage; 	//variable to store the voltage of analog output of gas sensor after beeing converted from (1024 to 5.0). 
int lightState;   	//whether light is turned on or off
float tempCelsius;	//current temp of room in celsius
float tempRef;		//temp refernce set by user
int fanState;		//the fan state, is it on or off
int heaterState;	//the heater state. is it on or off
int gasState;		//whether gas alarm is on or off (1 is fine 0 means gas level is high) active low
float fireVoltage;	//the voltage reading of the fire sensor (5V is no any file, 0V too much fire)
int fireState;		//whether there is fire or no
int PIRState; 		//whether the PIR sensor detected someone or not
//---------------------------------------------------- 

// CE , CSN , speed = 8MHZ 
//RF24 radio(RPI_V2_GPIO_P1_15, RPI_V2_GPIO_P1_24, BCM2835_SPI_SPEED_8MHZ); //physical pin 15 and 24
RF24 radio(22,0); //GPIO22 and CE0
uint8_t pipes[][6] = {"MSTR" ,"SLV1" };

using namespace std;
																			   
void readSLV(){
	ofstream logFile;
	logFile.open("safty_readings.log", ios::app);
	logFile<<ctime(&time_now);
	
	ret = radio.write(&send,sizeof(char[40]));
	if(ret == false){
		printf("failed to send request to SLV\n");
		logFile<<"failed to send request to SLV\n";
		logFile<<"runtime: "<<clock()<<endl;
		logFile<<"----------------------------------------- ";
		logFile.close();
		exit(1);
	}
	else{
		printf("read request was sent\n");
	}
	radio.startListening();
	//reset:
	
	// get lightVoltage
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&lightVoltage,sizeof(lightVoltage));
		printf("light Voltage = %f\n",lightVoltage);
		logFile << "light Voltage: " <<lightVoltage<<endl;
	}
	else{
		printf("failed to rececive light Voltage (timeout)\n");
		logFile<<"failed to rececive light Voltage (timeout)\n";
		//perror("failed to rececive light Voltage (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	// get reference voltage
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&refVoltage,sizeof(refVoltage));
		printf("reference Voltage = %f\n",refVoltage);
		logFile << "reference Voltage: " <<refVoltage<<endl;
	}
	else{
		printf("failed to rececive reference Voltage (timeout)\n");
		logFile<<"failed to rececive reference Voltage (timeout)\n";
		//perror("failed to rececive reference Voltage (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	// get light state
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&lightState,sizeof(lightState));
		printf("light State = %d\n",lightState);
		logFile << "light State: " <<lightState<<endl;
	}
	else{
		printf("failed to rececive light State (timeout)\n");
		logFile<<"failed to rececive light State (timeout)\n";
		//perror("failed to rececive light State (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	// get temp Celcius
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&tempCelsius,sizeof(tempCelsius));
		printf("tempreature Celcius = %f\n",tempCelsius);
		logFile << "tempreature Celcius: " <<tempCelsius<<endl;
	}
	else{
		printf("failed to rececive tempreature Celcius (timeout)\n");
		logFile<<"failed to rececive tempreature Celcius (timeout)\n";
		//perror("failed to rececive tempreature Celcius (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	// get temp Reference
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&tempRef,sizeof(tempRef));
		printf("tempreature Reference = %f\n",tempRef);
		logFile << "tempreature Reference: " <<tempRef<<endl;
	}
	else{
		printf("failed to rececive tempreature Reference (timeout)\n");
		logFile<<"failed to rececive tempreature Reference (timeout)\n";
		//perror("failed to rececive tempreature Reference (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	// get fan State
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&fanState,sizeof(fanState));
		printf("fan State = %d\n",fanState);
		logFile << "fan State: " <<fanState<<endl;
	}
	else{
		printf("failed to rececive fan State (timeout)\n");
		logFile<<"failed to rececive fan State (timeout)\n";
		//perror("failed to rececive fan State (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	// get heater State
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&heaterState,sizeof(heaterState));
		printf("heater State = %d\n",heaterState);
		logFile << "heater State: " <<heaterState<<endl;
	}
	else{
		printf("failed to rececive heater State (timeout)\n");
		logFile<<"failed to rececive heater State (timeout)\n";
		//perror("failed to rececive heater State (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	// get gasVoltage
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&gasVoltage,sizeof(gasVoltage));
		printf("gas voltage = %f\n",gasVoltage);
		logFile << "gas Voltage: " <<gasVoltage<<endl;
	}
	else{
		printf("failed to rececive gas voltage (timeout)\n");
		logFile<<"failed to rececive gas voltage (timeout)\n";
		//perror("failed to rececive gas voltage (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	
	//get gasState
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&gasState,sizeof(gasState));
		printf("gas state = %d\n",gasState);
		logFile << "gas state: " <<gasState<<endl;
	}
	else{
		printf("failed to rececive gas state (timeout)\n");
		logFile<<"failed to rececive gas state (timeout)\n";
		//perror("failed to rececive gas state (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	//get fireVoltage
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&fireVoltage,sizeof(fireVoltage));
		printf("fire Voltage= %f\n",fireVoltage);
		logFile << "fire Voltage: " <<fireVoltage<<endl;
	}
	else{
		printf("failed to rececive fire Voltage (timeout)\n");
		logFile<<"failed to rececive fire Voltage (timeout)\n";
		//perror("failed to rececive fire Voltage (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	//get fireState
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&fireState,sizeof(fireState));
		printf("fire State = %d\n",fireState);
		logFile << "fire State: " <<fireState<<endl;
	}
	else{
		printf("failed to rececive fire State (timeout)\n");
		logFile<<"failed to rececive fire State (timeout)\n";
		//perror("failed to rececive fire State (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	//get PIRState
	current_time = millis();
	timeout = false;
	while(!radio.available()){
		if(millis() - current_time > 500){
			timeout = true;
			break;
		}
	}
	
	if(!timeout){
		radio.read(&PIRState,sizeof(PIRState));
		printf("PIR State = %d\n",PIRState);
		logFile << "PIR State: " <<PIRState<<endl;
	}
	else{
		printf("failed to rececive PIR State (timeout)\n");
		logFile<<"failed to rececive PIR State (timeout)\n";
		//perror("failed to rececive PIR State (timeout)\n");
		//exit(1);
		//goto reset;
	}
	
	logFile<<"runtime: "<<clock()<<endl;
	logFile<<"----------------------------------------- ";
	logFile.close();
	
}
void printSLV(char m){ //m stands for mode{% = persentage, else = voltage}
	if(m == '%'){
		lightVoltage *= (100.0/5.0);
		refVoltage *= (100.0/5.0);
		gasVoltage *= (100.0/5.0);
		fireVoltage *= (100.0/5.0);
	}
	
	printf("--------------------------------\n");
	printf("light voltage = %f",lightVoltage);  	if(m == '%'){printf("%%");}
	printf("\nreference voltage = %f",refVoltage);	if(m == '%'){printf("%%");}
	printf("\nlight state = %d",lightState);
	printf("\ngas voltage = %f",gasVoltage);  	    if(m == '%'){printf("%%");}
	printf("\ngas State = %d\n",gasState);
	printf("fire Voltage = %f",fireVoltage);  		if(m == '%'){printf("%%");}
	printf("\nfire State = %d\n",fireState);
	printf("PIR State = %d\n",PIRState);
	printf("--------------------------------\n");
}

void setGUIReadings(){ //updating the file that GUI program uses to update its readings
	ofstream gui;
	gui.open("guiReadings.txt", ios::in | ios::out);
	
	gui<<lightVoltage<<endl;
	gui<<refVoltage<<endl;
	gui<<gasVoltage<<endl;
	gui<<lightState<<endl;
	gui<<tempCelsius<<endl;
	gui<<tempRef<<endl;
	gui<<fanState<<endl;
	gui<<heaterState<<endl;
	gui<<gasState<<endl;
	gui<<fireVoltage<<endl;
	gui<<fireState<<endl;
	gui<<PIRState<<endl;
	
}
void sendToServer(){ //send all reading to server
	
	stringstream command;
	
	
	command<<"cd /var/www/html/rpi && "; //x
	command<<"php temp.php 3 ";		//id of temp sensor
	command<<tempCelsius;				//current temp
	system(command.str().c_str());
	
	//cout<<"command: "<<command.str().c_str()<<endl;
	
	command.str("");
	command<<"cd /var/www/html/rpi && "; //x
	command<<"php update.php 1 "; //sid = 1 is for sensor "light_1"
	command<<lightState;				//light alarm 
	system(command.str().c_str());
	
	command.str("");
	command<<"php update.php 2 ";           //sid = 2 if for sensor fire_1
	command<<fireState;                //light alarm
	system(command.str().c_str());
	
	command.str("");
	command<<"php update.php 3 ";
	command<<fanState||heaterState;		//temp fan and heater, are any of them working
	command<<"&val=";
	command<<tempRef;					//value of reference temp
	system(command.str().c_str());
	
	command.str("");
	command<<"php update.php 4 ";			//pir sensor
	command<<PIRState;					//pir state, found someone or nor
	system(command.str().c_str());
	
	command.str("");
	command<<"php update.php 5 ";			//home door
	command<<"1";						//whether the door is open  or not
	system(command.str().c_str());
	
	command.str("");
	command<<"php update.php 6 ";			//tv_1
	command<<"0";//////////////////////////////////still need to be made////////////////////////////////////
	system(command.str().c_str());
	
	for(int i = 7; i<=31 ; i++){
		command.str("");
		command<<"php update.php "<<i<<" 0";		
		system(command.str().c_str());
		//cout<<"command: "<<command.str().c_str()<<endl;
	}
	
	/*
	command<<"update.php?sid=7&state=";			//light_2
	command<<"0";
	command<<"&sid=8&state=";			//fire_2
	command<<"0";
	command<<"&sid=9&state=";
	command<<"0";
	command<<"&sid=10&state=";
	command<<"0";
	command<<"&sid=11&state=";
	command<<"0";
	command<<"&sid=12&state=";
	command<<"0";
	command<<"&sid=13&state=";
	command<<"0";
	command<<"&sid=14&state=";
	command<<"0";
	command<<"&sid=15&state=";
	command<<"0";
	command<<"&sid=16&state=";
	command<<"0";
	command<<"&sid=17&state=";
	command<<"0";
	command<<"&sid=18&state=";
	command<<"0";
	command<<"&sid=19&state=";
	command<<"0";
	command<<"&sid=20&state=";
	command<<"0";
	command<<"&sid=21&state=";
	command<<"0";
	command<<"&sid=22&state=";
	command<<"0";
	command<<"&sid=23&state=";
	command<<"0";
	command<<"&sid=24&state=";
	command<<"0";
	command<<"&sid=25&state=";
	command<<"0";
	command<<"&sid=26&state=";
	command<<"0";
	command<<"&sid=27&state=";
	command<<"0";
	command<<"&sid=28&state=";
	command<<"0";
	command<<"&sid=29&state=";
	command<<"0";
	command<<"&sid=30&state=";
	command<<"0";
	command<<"&sid=31&state=";
	command<<"0";
	
	cout<<"command: "<<command.str().c_str()<<endl
	*/
}

void setup(){
	
	radio.begin(); //startup the nRF24 module
	radio.setRetries(15,15);
	radio.openWritingPipe(pipes[0]); //for Rpi to write on, the 
	radio.openReadingPipe(1,pipes[1]); //for reading from ARD1, ARD1 
	radio.stopListening();
}
/*
int fifo_stuff(){
	
	printf("entered\n");
	fifo_state = mkfifo(fifo_name,0666);
	
	if(fifo_state < 0){ //file already exists
		unlink(fifo_name);//delete the file
	}
	*/
	//printf("waiting to open file\n");
	//fp = fopen(fifo_name , "w"); //fopen returns null if error happend, else it returns the pointer
	/*
	if(fp == NULL){
		perror("failed to open fifo file: ");
		unlink(fifo_name);
		exit(1);
	}
	 */
	 /*
	printf("opend file\n");
	sprintf(temp,"%f",lightVoltage);
	fputs("anas",fp);
	fclose(fp);
	printf("close file\n");
	return 0;
	//usleep(3000);
	//unlink(fifo_name);
}
*/


int main(int argc, char* argv[]){
	
	setup();
	readSLV();
	setGUIReadings();
	
	
	if(strcmp("print",argv[1]) == 0 ){
		cout<<"in print& \n";
		if(argc >=3){
			
			if(strcmp("%",argv[2]) == 0){
				printf("1\n");
				printSLV('%');
				
				if(argc>=4){
					if(strcmp("server",argv[3])==0)
						sendToServer();
				}
			}
			
			else if(strcmp("server",argv[1])==0){
				printf("2\n");
				sendToServer();
				printSLV('v');
			}
				
		}
		else {//if(strcmp("v",argv[2])==0){
			printf("3\n");
			printSLV('v');
		}
		printf("eixt print\n");
	}
	
	else if(strcmp("server",argv[1])==0){
		sendToServer();
	}
	printf("eixt \n");
	//fifo_stuff();
	
	return 0;
}
