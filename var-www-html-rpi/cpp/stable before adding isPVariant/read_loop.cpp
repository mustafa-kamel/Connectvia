#include <stdlib.h>     //to use system, NULL, EXIT_FAILURE
#include <unistd.h>		//to use sleep

int main(){

	while(1){
		sleep(3);
		system("sudo ./collector_get_readings");
	}
}
