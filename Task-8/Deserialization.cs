//Deserialization: Converting a JSON string back to an object
using System;
using System.Text.Json;

public class Person {
   public string Name { get; set; }
   public int    Age  { get; set; }
}
public class Program {
   public static void Main() {
    string jsonString = "{\"Name\":\"John\", \"Age\"\:30}";
    Person person = JsonSerializer.Deserialize<Person>(jsonString);
    Console.WriteLine("Deserialized Object:");
    Console.WriteLine($"Name: {person.Name}, Age: {person.Age}");
   }
}
