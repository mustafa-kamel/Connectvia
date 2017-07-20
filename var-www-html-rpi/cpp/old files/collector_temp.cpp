#include <unistd.h> 		//to use usleep()
#include <stdio.h>  		//to use printf()
#include <cstdlib>  		//to use atof , atoi or atol
#include <string.h> 		//to used strcmp()
#include "RF24/RF24.h"		//to use nRF24 module
#include <RF24/printf.h>	//to print wirless detailes
#include <fstream>			//to write to text file
#include <iostream>			//to use << and using std
#include <ctime>			//to get the time now

using namespace std;

const uint8_t pipes[][6] = {"MSTR","SLV1"};

// Setup for GPIO 22(#15) CE and CE0 (#24) CSN with SPI Speed @ 8Mhz
//RF24 radio(RPI_V2_GPIO_P1_15, RPI_V2_GPIO_P1_24, BCM2835_SPI_SPEED_8MHZ); //pin numbers are physical
RF24 radio(22,0);

char ref[40] = "tempReference";
char ACK[40] = "ACK";
char NACK[40]= "NACK";
char UC[40]  = "No such command";
char incoming[40];
float tempRef;
time_t time_now = time(0);

int current_time;
bool timeout;
int write_success;

int num1;
int num2;
bool ret = false;
int retry = 0;

void setup(){
	
	radio.begin();
	//printf_begin();
	radio.setRetries(15,15);
	radio.openWritingPipe(pipes[0]);
	radio.openReadingPipe(1,pipes[1]);
	//radio.printDetails();
}

void loop(){
	radio.stopListening();
	
	ofstream logFile;
	logFile.open("log_server.log", ios::app);
	logFile << ctime(&time_now);
	
	printf("server sent temperature reference: %f\n",tempRef);
	logFile<<"server sent temperature reference: "<<tempRef<<endl;
	
	//write to arduino
	printf("sending mode: %s\n",ref);
	logFile<<"sending mode: "<<ref<<endl;
	radio.write(&ref,sizeof(char[40]));
	radio.startListening();
	
	
	//receive from arduino
	timeout = false;
	current_time = millis();
	while(!radio.available()){
		if(millis()-current_time > 1000){
			timeout = true;
			break;
		}
	}
	
	if(timeout){
		printf("timeout, failed to receive.\n");
		logFile<<"timeout, failed to receive.\n";
		radio.read(&incoming,sizeof(char[40]));
		printf("in buffer: %s\n",incoming);
		logFile<<"in buffer: "<<incoming<<endl;
	}
	
	else{
		while(radio.available()){
			radio.read(&incoming,sizeof(char[40])); // arduino should send us ACK if everything is ok
		}
		printf("incoming: %s\n",incoming);
		logFile<<"incoming: "<<incoming<<endl;
		
		num1 = strcmp(incoming , ACK);
		if(num1 != 0){
			num2 = strcmp(incoming , UC);
			if(num2 == 0){
				printf("recived \"unknown command\"\n");
				logFile<<"recived \"unknown command\"\n";
			}
			else{
				printf("unexpected respond: %s\n",incoming);
				logFile<<"unexpected respond: "<<incoming<<endl;
			}
		}
		else{
			radio.stopListening();
			
			write_success = 0;
			retry = 0;
			while(!write_success && retry < 5){
				usleep(100);
				write_success = radio.write(&tempRef,sizeof(float));
				retry++;
			}
			printf("received from Arduino ACK\n");
			logFile<<"received from Arduino ACK\n";
			printf("RPi: writing: %f\n",tempRef);
			logFile<<"RPi: writing: "<<tempRef<<endl;
			printf("write_success = %d\n",write_success);
			logFile<<"write_success = "<<write_success<<endl;
			
		}
	}

logFile<<"runtime: "<<clock()<<endl;
logFile<<"----------------------------------------- ";
logFile.close();
	
}


int main(int argc, char ** argv){
	tempRef = atof(argv[1]);
	
	setup();
	loop();
	return 0;
}
