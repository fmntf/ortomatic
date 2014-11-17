#include <Wire.h>
#include <HIH61XX.h>
//  Create an HIH61XX with I2C address 0x27, powered by pin 8
HIH61XX hih(0x27);


#include <OneWire.h>
// OneWire DS18S20, DS18B20, DS1822 Temperature Example
//
// http://www.pjrc.com/teensy/td_libs_OneWire.html
//
// The DallasTemperature library can do all this work for you!
// http://milesburton.com/Dallas_Temperature_Control_Library
OneWire  ds(10);  // on pin 10

//   stores data read from serial port
byte serialRead;


void setup()
{
  Serial.begin(9600);
  Serial.println("Serial port ready.");
  
  Wire.begin();
  hih.start();

  Serial.println("Setup complete.");
}


void loop()
{
  if (Serial.available() > 0) {
    serialRead = Serial.read();
    if (serialRead == 'T') {
      readDallasTemp();
    } else {
      hih.commandProcess(Serial, serialRead);
    }
  }

  delay(100);
}


byte addr[8]; // dallas 1wire addr
bool dallasFound = false; // true when dallas is found for the first time

void readDallasTemp() {
  byte i;
  byte present = 0;
  byte type_s;
  byte data[12];
  float celsius;
  
  if (!dallasFound) {
    if ( !ds.search(addr)) {
      Serial.println("Search failed! Try again.");
      ds.reset_search();
      delay(250);
      return;
    } else dallasFound = true;
  }
  
  if (OneWire::crc8(addr, 7) != addr[7]) {
      Serial.println("CRC is not valid!");
      return;
  }
 
  // the first ROM byte indicates which chip
  switch (addr[0]) {
    case 0x10:
      type_s = 1;
      break;
    case 0x28:
      type_s = 0;
      break;
    case 0x22:
      type_s = 0;
      break;
    default:
      Serial.println("Device is not a DS18x20 family device.");
      return;
  } 

  ds.reset();
  ds.select(addr);
  ds.write(0x44,1);         // start conversion, with parasite power on at the end
  
  delay(1000);     // maybe 750ms is enough, maybe not
  // we might do a ds.depower() here, but the reset will take care of it.
  
  present = ds.reset();
  ds.select(addr);    
  ds.write(0xBE);         // Read Scratchpad

  for ( i = 0; i < 9; i++) {           // we need 9 bytes
    data[i] = ds.read();
  }

  unsigned int raw = (data[1] << 8) | data[0];
  if (type_s) {
    raw = raw << 3; // 9 bit resolution default
    if (data[7] == 0x10) {
      // count remain gives full 12 bit resolution
      raw = (raw & 0xFFF0) + 12 - data[6];
    }
  } else {
    byte cfg = (data[4] & 0x60);
    if (cfg == 0x00) raw = raw << 3;  // 9 bit resolution, 93.75 ms
    else if (cfg == 0x20) raw = raw << 2; // 10 bit res, 187.5 ms
    else if (cfg == 0x40) raw = raw << 1; // 11 bit res, 375 ms
    // default is 12 bit resolution, 750 ms conversion time
  }
  celsius = (float)raw / 16.0;
  Serial.println(celsius);
}



