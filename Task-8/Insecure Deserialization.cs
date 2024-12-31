using System;
using System.IO;
using System.Runtime.Serialization.Formatters.Binary;

[Serializable]
public class Exploitable
{
    public string Command { get; set; }

    public Exploitable()
    {
        // Executes the command during deserialization
        System.Diagnostics.Process.Start(Command);
    }
}

class Program
{
    static void Main()
    {
        byte[] data = GetUntrustedData();
        var formatter = new BinaryFormatter();
        var obj = formatter.Deserialize(new MemoryStream(data)); // Insecure deserialization
    }
}
//The BinaryFormatter class is inherently insecure because it can deserialize arbitrary object types.
//During deserialization, BinaryFormatter can invoke constructors and methods (ISerializable.GetObjectData, property setters), 
//allowing attackers to execute arbitrary code.