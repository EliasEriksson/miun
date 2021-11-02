﻿using System;
using System.Collections.Generic;


namespace App
{
    /**
     * Custom exception to throw on error
     */
    public class ParseException : Exception
    {
    }

    internal static class Program
    {
        /**
         * Maps the calculated day number to a week day. 
         */
        private static readonly Dictionary<int, string> DayMap = new Dictionary<int, string>
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
         * Reads the sys args and converts them to 32 bit ints
         */
        private static (int, int, int) ReadInput(string[] args)
        {
            
            if (args.Length == 3)
            {
                return (Convert.ToInt32(args[0]), Convert.ToInt32(args[1]), Convert.ToInt32(args[2]));
            }
            throw new ParseException();
        }

        /**
         * Processes a given input according to Zeller's algorithm
         */
        private static void ProcessInput(int year, int month, int day)
        {
            var century = year / 100;
            year %= 100;
            var dayOfWeek = (day + ((13 * (month + 1)) / 5) + year + (year / 4) + (century / 4) + 5 * century) % 7;
            Console.WriteLine(dayOfWeek);
            Console.WriteLine($"You were born on a {DayMap[dayOfWeek]}.");
        }

        /**
         * Entry point of the program and error handling
         */
        private static int Main(string[] args)
        {
            try
            {
                var (year, month, day) = ReadInput(args);
                ProcessInput(year, month, day);
            }
            catch (OverflowException)
            {
                Console.WriteLine("You are either VERY VERY old or VERY VERY young.");
            }
            catch (FormatException)
            {
                Console.WriteLine("You didnt only provide numbers.");
            }
            catch (ParseException)
            {
                Console.WriteLine("Please provide year month and day as sys arguments on format: yyyy mm dd");
                return 1;
            }

            return 0;
        }
    }
}