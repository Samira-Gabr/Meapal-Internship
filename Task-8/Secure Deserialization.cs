using System;
using System.Text.Json;
using System.IO;
public class SafeDeserialization
{
    public string Name { get; set; }
    public int Age { get; set; }
}
class Program
{
    static void Main()
    {
        string jsonInput = GetUntrustedInput();
        try
        {
            // Deserialize using System.Text.Json (safer alternative)
            var options = new JsonSerializerOptions
            {
                PropertyNameCaseInsensitive = true, 
                MaxDepth = 5 
            };
            SafeDeserialization obj = JsonSerializer.Deserialize<SafeDeserialization>(jsonInput, options);
            Console.WriteLine($"Name: {obj.Name}, Age: {obj.Age}");
        }
        catch (Exception ex)
        {
            Console.WriteLine("Invalid or malicious input detected: " + ex.Message);
        }
    }
    static string GetUntrustedInput()
    {
        return "{ \"Name\": \"John\", \"Age\": 30 }";
    }
}
//System.Text.Json Unlike BinaryFormatter, 
//this framework is designed for deserializing JSON data and does not execute any code during deserialization. 
//It is a data-only serialization framework.