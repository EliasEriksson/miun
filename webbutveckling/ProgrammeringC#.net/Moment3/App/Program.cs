using System;
using App.Book;

namespace App
{
    internal static class Program
    {
        private static readonly Book.Book Book = new();

        /**
         * Reads a string value from user input
         *
         * if user input is only an X the operation is interrupted
         * 
         */
        private static string ReadString(string message, bool clear = true)
        {
            var input = "";
            while (string.IsNullOrEmpty(input))
            {
                if (clear) Console.Clear();
                Console.WriteLine("X to exit.");
                Console.WriteLine(message);
                input = Console.ReadLine();
            }

            if (input.ToLower() == "x")
            {
                throw new CancelOperation();
            }

            return input;
        }

        /**
         * Reads a string from user input and converts to int
         *
         * if the conversion fails the user is prompted again.
         */
        private static int ReadInt(string message, bool clear = true)
        {
            while (true)
            {
                if (clear) Console.Clear();
                try
                {
                    return Convert.ToInt32(ReadString(message, false));
                }
                catch (FormatException)
                {
                    Console.WriteLine("That is not a number!");
                }
                catch (OverflowException)
                {
                    Console.WriteLine("Number needs to fit in 32 bit int.");
                }
            }
        }

        /**
         * prompts the user for name and content and adds page to the guestbook
         */
        private static void AddPage()
        {
            try
            {
                var name = ReadString("Your name: ");
                var content = ReadString("What is on your mind: ");
                Book.AddPage(name, content);
                Book.Save();
            }
            catch (CancelOperation)
            {
            }
        }

        /**
         * prompts the user for an index of a page to remove
         *
         * if the index exists the page is removed
         */
        private static void RemovePage()
        {
            try
            {
                if (Book.NumberOfPages() == 0) return;
                int index;
                do
                {
                    index = ReadInt($"Which page do you want to delete?\n\n{Book.GetPages()}");
                } while (!(0 <= index && index < Book.NumberOfPages()));

                Book.RemovePage(index);
                Book.Save();
            }
            catch (CancelOperation)
            {
            }
        }

        private static void Main()
        {
            while (true)
            {
                Console.Clear();
                Console.WriteLine("E L I A S   G U E S T B O O K\n");
                Console.WriteLine("1. Write in the guestbook");
                if (Book.NumberOfPages() > 0) Console.WriteLine("2. Remove a post\n");
                Console.WriteLine(Book.GetPages());

                try
                {
                    var command = ReadString("", false);
                    switch (command)
                    {
                        case null:
                            continue;
                        case "1":
                            AddPage();
                            break;
                        case "2":
                            RemovePage();
                            break;
                    }
                }
                catch (CancelOperation)
                {
                    break;
                }
            }
        }
    }
}