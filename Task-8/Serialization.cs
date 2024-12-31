using System;
using System.Text.Json;

public class Person {
  public string Name { get; set; }
  public int    Age  { get; set; }
}
public class Program {
   public static void Main() {
    Person person = new Person { Name = "John", Age = 30 };
    string jsonstring = JsonSerializer.Serialize(person);
    Console.WriteLine("Serialized JSON: ");
    Console.WriteLine(jsonString);
   }
}