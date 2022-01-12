using System.Threading;

namespace App.Game.Player
{
    /**
     * an easy ai. this ai really have no idea about what it is doing.
     */
    public class EasyAi : Ai
    {
        public EasyAi(Marker marker) : base(marker)
        {
        }

        /**
         * Easy ai play.
         *
         * just pick a random empty slot.
         */
        public override void Play(Board board)
        {
            board.Draw(0, 0);
            Thread.Sleep(250);
            var availableCoordinates = board.GetUnchangedPositions();
            board.Set(availableCoordinates[this.RandomInt(availableCoordinates.Count)], this.GetMarker());
        }

        public override string ToString()
        {
            return $"Easy {base.ToString()}";
        }
    }
}