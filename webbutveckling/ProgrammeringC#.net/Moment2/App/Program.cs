using System;
using System.Collections.Generic;


namespace App
{
    /**
     * Custom exception to throw on error when parsing goes bad.
     */
    public class ParseException : Exception
    {
    }

    internal static class Program
    {
        /**
         * Maps the calculated day number to a week day. 
         */
        private static readonly Dictionary<int, string> DayMap = new()
        {
            {2, "monday"},
            {3, "tuesday"},
            {4, "wednesday"},
            {5, "thursday"},
            {6, "friday"},
            {0, "saturday"},
            {1, "sunday"}
        };

        /**
         * Searches for a specific argument in args and converts its value to an int.
         */
        private static int ParseArgs(string[] args, string arg, Func<int, bool> validator)
        {
            for (var i = 0; i < args.Length - 1; i++)
            {
                if (args[i] != arg) continue;
                try
                {
                    var input = Convert.ToInt32(args[i + 1]);
                    if (validator(input))
                    {
                        return input;
                    }

                    throw new ParseException();
                }
                catch (OverflowException)
                {
                    throw new ParseException();
                }
                catch (FormatException)
                {
                    throw new ParseException();
                }
            }

            throw new ParseException();
        }

        /**
         * Prompts the user for an integer input with a helpful message
         */
        private static int PromptUser(string message)
        {
            while (true)
            {
                Console.Clear();
                Console.Write(message);
                try
                {
                    return Convert.ToInt32(Console.ReadLine());
                }
                catch (OverflowException)
                {
                }
                catch (FormatException)
                {
                }
            }
        }
        
        /**
         * Checks if a year is valid.
         */
        private static bool ValidateYear(int year)
        {
            return year is > 0 and < 10_000;
        }

        /**
         * Checks if a month is valid.
         */
        private static bool ValidateMonth(int month)
        {
            return month is > 0 and <= 12;
        }

        /**
         * Checks if a day is valid.
         */
        private static bool ValidateDay(int day)
        {
            return day is > 0 and <= 31;
        }
        
        /**
         * Get a valid year from user input
         *
         * The args are first parsed to see if there is a valid year.
         * If no valid year is found the user is prompted to give one instead.
         */
        private static int GetYear(string[] args)
        {
            try
            {
                return ParseArgs(args, "-y", ValidateYear);
            }
            catch (ParseException)
            {
                while (true)
                {
                    const string message = "Please provide a year AD on format yyyy: ";
                    var year = PromptUser(message);
                    if (ValidateYear(year))
                    {
                        return year;
                    }
                }
            }
        }

        /**
         * Get a valid month from user input.
         *
         * The args are first parsed to see if there is a valid month.
         * If no valid month is found the user is prompted to give one instead.
         */
        private static int GetMonth(string[] args)
        {
            try
            {
                return ParseArgs(args, "-m", ValidateMonth);
            }
            catch (ParseException)
            {
                while (true)
                {
                    const string message = "Please provide a month on format mm: ";
                    var month = PromptUser(message);
                    if (ValidateMonth(month))
                    {
                        return month;
                    }
                }
            }
        }

        /**
         * Get a valid day from user input.
         *
         * The args are first parsed to see if there is a valid day.
         * If no valid day is found the user is prompted to give one instead.
         */
        private static int GetDay(string[] args)
        {
            try
            {
                return ParseArgs(args, "-d", ValidateDay);
            }
            catch (ParseException)
            {
                while (true)
                {
                    const string message = "Please provide a day on format dd: ";
                    var day = PromptUser(message);
                    if (ValidateDay(day))
                    {
                        return day;
                    }
                }
            }
        }

        /**
         * Processes a given input according to Zeller's algorithm
         */
        private static void Process(int year, int month, int day)
        {
            Console.Clear();
            if (month < 3)
            {
                month += 12;
                year--;
            }
            var century = year / 100;
            year %= 100;
            var dayOfWeek = (day + ((13 * (month + 1)) / 5) + year + (year / 4) + (century / 4) + 5 * century) % 7;
            Console.WriteLine($"You were born on a {DayMap[dayOfWeek]}.");
        }

        /**
         * Entry point of the program.
         */
        private static int Main(string[] args)
        {
            Process(GetYear(args), GetMonth(args), GetDay(args));
            return 0;
        }
    }
}