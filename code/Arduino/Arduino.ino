#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
 
const char* ssid = "habbeh1";
const char* password = "YouSe!f99MohAmma@d81HabBe#h03ComPute$r76InfOrmatio%n72SysTe^m0";



#define SENSOR  5 //its equal pin number D1
long currentMillis = 0;
long previousMillis = 0;
int interval = 1000;
boolean ledState = LOW;
float calibrationFactor = 6.5;
volatile byte pulseCount;
byte pulse1Sec = 0;
float flowRate;
unsigned int flowMilliLitres;
unsigned long totalMilliLitres;
int CounterS = 0;
void IRAM_ATTR pulseCounter()
{
  pulseCount++;
}

 
void setup () {
 
  Serial.begin(115200);
 
  WiFi.begin(ssid, password);
 
  while (WiFi.status() != WL_CONNECTED) {
 
    delay(1000);
    Serial.println("Connecting..");
/*                                        */
    pinMode(SENSOR, INPUT_PULLUP);
    pulseCount = 0;
    flowRate = 0.0;
    flowMilliLitres = 0;
    totalMilliLitres = 0;
    previousMillis = 0;
    attachInterrupt(digitalPinToInterrupt(SENSOR), pulseCounter, FALLING);
     
  }
 
}
 
void loop() {

currentMillis = millis();
  if (currentMillis - previousMillis > interval) {
    
    pulse1Sec = pulseCount;
    pulseCount = 0;

    // Because this loop may not complete in exactly 1 second intervals we calculate
    // the number of milliseconds that have passed since the last execution and use
    // that to scale the output. We also apply the calibrationFactor to scale the output
    // based on the number of pulses per second per units of measure (litres/minute in
    // this case) coming from the sensor.
    flowRate = ((1000.0 / (millis() - previousMillis)) * pulse1Sec) / calibrationFactor;
    previousMillis = millis();

    // Divide the flow rate in litres/minute by 60 to determine how many litres have
    // passed through the sensor in this 1 second interval, then multiply by 1000 to
    // convert to millilitres.
    flowMilliLitres = (flowRate / 6) * 1000; //devided by 6 = minit have 6 *(10 seconds "every loop")

    // Add the millilitres passed in this second to the cumulative total
    totalMilliLitres += flowMilliLitres;
    
    // Print the flow rate for this second in litres / minute
    Serial.print(CounterS);
    Serial.print("Flow rate: ");
    Serial.print(int(flowRate));  // Print the integer part of the variable
    Serial.print("L/min");
    Serial.print("\t");       // Print tab space

    // Print the cumulative total of litres flowed since starting
    Serial.print("Output Liquid Quantity: ");
    Serial.print(totalMilliLitres);
    Serial.print("mL / ");
    Serial.print(totalMilliLitres / 1000);
    Serial.println("L");
    CounterS++;
  }

  

 /*^^^^^^^^^^^^^^^^^^^^^^^^*/
    
  if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
    if(CounterS >= 60){
    HTTPClient http;   //Declare an object of class HTTPClient
    String URL = "http://almanahelschool.com/water.php?totalML="+String(totalMilliLitres)+"&DeviceId=9999";
    http.begin(URL);    //Specify request destination
    int httpCode = http.GET();  //Send the request

    http.end();   //Close connection
    Serial.print("data sending done");
    Serial.println(totalMilliLitres);
    totalMilliLitres =0 ; // to calculate new reading
    CounterS = 0; //Send a request every 10 minits
    }
  } 
  
  delay(10000); //wating for every loop   
}
