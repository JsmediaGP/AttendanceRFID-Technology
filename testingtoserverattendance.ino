// #include <WiFi.h>  
// #include <HTTPClient.h>  
// #include <SPI.h>  
// #include <MFRC522.h>  

// #define SS_PIN 21  
// #define RST_PIN 22  

// const char* ssid = "Js'Media";  
// const char* password = "password";  
// const char* serverUrl = "http://192.168.200.52:8000/api/rfid-scan";  

// // Add a hall name variable  
// const String hallname = "Hall A"; // Change this to your desired hall name  

// MFRC522 mfrc522(SS_PIN, RST_PIN);  
// WiFiClient client;  

// void setup() {  
//     Serial.begin(115200);  
//     SPI.begin();  
//     mfrc522.PCD_Init();  

//     WiFi.begin(ssid, password);  
//     while (WiFi.status() != WL_CONNECTED) {  
//         delay(1000);  
//         Serial.println("Connecting to WiFi...");  
//     }  
//     Serial.println("Connected to WiFi");  
// }  

// void loop() {  
//     if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {  
//         return;  
//     }  

//     String rfid = "";  
//     for (byte i = 0; i < mfrc522.uid.size; i++) {  
//         rfid += String(mfrc522.uid.uidByte[i], HEX);  
//     }  
//     rfid.toUpperCase();  
//     Serial.println("Scanned RFID: " + rfid);  
    
//     if (WiFi.status() == WL_CONNECTED) {  
//         HTTPClient http;  
//         http.begin(serverUrl);  
//         http.addHeader("Content-Type", "application/x-www-form-urlencoded");  

//         // Include hallname in post data  
//         String postData = "rfid=" + rfid + "&hallname=" + hallname; // Add hallname to the post data  
//         int httpResponseCode = http.POST(postData);  
        
//         if (httpResponseCode > 0) {  
//             String response = http.getString();  
//             Serial.println("Server Response: " + response);  
//         } else {  
//             Serial.println("Error in sending request");  
//         }  

//         http.end();  
//     }  

//     delay(2000);  
// }  

//working fine
// #include <WiFi.h>  
// #include <HTTPClient.h>  
// #include <SPI.h>  
// #include <MFRC522.h>  

// #define SS_PIN 21  
// #define RST_PIN 22  

// const char* ssid = "Js'Media";  
// const char* password = "password";  
// const char* serverUrl = "http://192.168.200.52:8000/api/rfid-scan";  

// // Add a hall name variable  
// const String hallname = "Hall A"; // Change this to your desired hall name  

// MFRC522 mfrc522(SS_PIN, RST_PIN);  
// WiFiClient client;  

// void setup() {  
//     Serial.begin(115200);  
//     SPI.begin();  
//     mfrc522.PCD_Init();  

//     WiFi.begin(ssid, password);  
//     while (WiFi.status() != WL_CONNECTED) {  
//         delay(1000);  
//         Serial.println("Connecting to WiFi...");  
//     }  
//     Serial.println("Connected to WiFi");  
// }  

// void loop() {  
//     // Check for new RFID card  
//     if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {  
//         return;  
//     }  

//     // Read RFID UID  
//     String rfid = "";  
//     for (byte i = 0; i < mfrc522.uid.size; i++) {  
//         rfid += String(mfrc522.uid.uidByte[i], HEX);  
//     }  
//     rfid.toUpperCase();  
//     Serial.println("Scanned RFID: " + rfid);  
    
//     if (WiFi.status() == WL_CONNECTED) {  
//         HTTPClient http;  
//         http.begin(serverUrl);  
//         http.addHeader("Content-Type", "application/x-www-form-urlencoded");  

//         // Include hallname in post data  
//         String postData = "rfid=" + rfid + "&hallname=" + hallname; // Add hallname to the post data  
//         int httpResponseCode = http.POST(postData);  
        
//         if (httpResponseCode > 0) {  
//             String response = http.getString();  
//             Serial.println("Server Response: " + response);  
            
//             // Optionally, handle the response here  
//             // Print response and determine further actions based on response content  
//             Serial.println("Response: " + response);  
            
//             // Example of checking for registration requirement based on JSON response  
//             if (response.indexOf("RFID not found") != -1) { // Adjust according to your actual response  
//                 Serial.println("RFID not found, prompt user to register.");  
//                 // In a real scenario, you might also display this on an LCD or take actions accordingly  
//             }  
//         } else {  
//             Serial.println("Error in sending request, Code: " + String(httpResponseCode));  
//         }  

//         http.end();  
//     } else {  
//         Serial.println("WiFi not connected!");  
//     }  

//     delay(2000);  
// }  




//another one 
#include <WiFi.h>  
 #include <HTTPClient.h>
#include <SPI.h>  
#include <MFRC522.h>  

#define SS_PIN 21  
#define RST_PIN 22  

const char* ssid = "Js'Media"; // Your SSID  
const char* password = "password"; // Your Wi-Fi password  
const char* serverUrl = "http://192.168.200.52:8000/api/rfid-scan"; // Laravel API URL  

MFRC522 rfid(SS_PIN, RST_PIN); // Create MFRC522 instance  

void setup() {  
    Serial.begin(115200);  
    WiFi.begin(ssid, password);  
    
    while (WiFi.status() != WL_CONNECTED) {  
        delay(1000);  
        Serial.println("Connecting to WiFi...");  
    }  
    Serial.println("Connected to WiFi");  

    SPI.begin(); // Init SPI bus  
    rfid.PCD_Init(); // Init MFRC522  
    Serial.println("RFID reader ready");  
}  

void loop() {  
    if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial()) {  
        return; // Return if there's no new card or read error  
    }  

    String rfidCode = "";  
    for (byte i = 0; i < rfid.uid.size; i++) {  
        rfidCode += String(rfid.uid.uidByte[i], HEX);  
    }  

    String hallName = "CLT"; // Replace this with actual hall name if needed  
    sendRfidToServer(rfidCode, hallName); // Send to server  
    rfid.PICC_HaltA(); // Halt PICC  
}  

void sendRfidToServer(String rfidCode, String hallName) {  
    if (WiFi.status() == WL_CONNECTED) {  
        WiFiClient client;  
        HTTPClient http;  

        http.begin(client, serverUrl);  
        http.addHeader("Content-Type", "application/json");  

        String jsonBody = "{\"rfid\":\"" + rfidCode + "\",\"hall_name\":\"" + hallName + "\"}";  
        int httpResponseCode = http.POST(jsonBody);  

        // Handle response if needed  
          // Check response code  
        if (httpResponseCode > 0) {  
            String response = http.getString(); // Get the response from the server  
            Serial.println("Response Code: " + String(httpResponseCode));  
            Serial.println("Response: " + response);  
        } else {  
            Serial.println("Error in sending request, Code: " + String(httpResponseCode)); // Print error  
        } 
        http.end();  
    } else {  
        Serial.println("WiFi Disconnected");  
    }  
}  