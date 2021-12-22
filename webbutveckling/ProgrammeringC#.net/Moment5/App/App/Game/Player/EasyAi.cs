using System.Threading;

namespace App.Game.Player
{
    public class EasyAi : Ai
    {
        public EasyAi(Marker marker) : base(marker)
        {
        }

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