using System;
using System.Collections.Generic;
using App.Book;

namespace App
{
    internal static class Program
    {
        private static readonly Book.Book Book = new();
        private static readonly Dictionary<string, Action> UserActions = new()
        {
            {"1", AddPage},
            {"2", RemovePage}
        };

        /**
         * returns true if the input string is anything but null
         */
        private static bool ValidateNotNull(string input)
        {
            return input != null;
        }

        /**
         * returns true if the input string is anything but empty or null
         *
         * used as a parameter for ReadString
         */
        private static bool ValidateNotEmpty(string input)
        {
            return !string.IsNullOrEmpty(input);
        }
        
        /**
         * Reads a string value from user input
         *
         * if user input is only an X the operation is interrupted
         * 
         */
        private static string ReadString(string message, Func<string, bool> validator, bool clear = true)
        {
            string input;
            do
            {
                if (clear) Console.Clear();
                Console.WriteLine("X to exit.");
                Console.WriteLine(message);
                input = Console.ReadLine();
                if (input?.ToLower() == "x")
                {
                    throw new CancelOperation();
                }
            } while (!validator(input));

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
                    return Convert.ToInt32(ReadString(message, ValidateNotEmpty, false));
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
                var name = ReadString("Your name: ", ValidateNotEmpty);
                var content = ReadString("What is on your mind: ", ValidateNotEmpty);
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
                    var action = ReadString("", ValidateNotNull, false);
                    if (UserActions.ContainsKey(action))
                    {
                        UserActions[action]();
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