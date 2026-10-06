"""Check publication hygiene without running the applications or remote sync."""
from pathlib import Path
import os
import re
import subprocess
import unittest

ROOT = Path(__file__).resolve().parents[1]


class RepositoryTest(unittest.TestCase):
    def test_tracked_files_exclude_private_artifacts(self):
        files = subprocess.check_output(['git', 'ls-files'], cwd=ROOT, text=True).splitlines()
        forbidden = [name for name in files if (
            Path(name).name in ('.env', '.env.prod', '.DS_Store')
            or Path(name).suffix in ('.sql', '.zip', '.pem', '.key', '.bundle')
            or name.startswith('.local-backups/')
        )]
        self.assertEqual(forbidden, [])

    def test_no_known_credential_formats_in_published_text(self):
        files = subprocess.check_output(['git', 'ls-files'], cwd=ROOT, text=True).splitlines()
        pattern = re.compile(r'AIza[0-9A-Za-z_-]{30,}|xkeysib-[\w-]+|-----BEGIN (?:RSA |EC |OPENSSH )?PRIVATE KEY-----')
        for name in files:
            try:
                contents = (ROOT / name).read_text()
            except (UnicodeError, OSError):
                continue
            self.assertIsNone(pattern.search(contents), name)

    def test_sync_help_works_without_credentials(self):
        result = subprocess.run(['bash', 'site-sync.sh', '--help'], cwd=ROOT,
                                capture_output=True, text=True)
        self.assertEqual(result.returncode, 0, result.stderr)
        self.assertIn('Usage:', result.stdout)

    def test_sync_requires_remote_configuration_before_running_commands(self):
        # HOME directory and local .env are irrelevant in this temporary script copy.
        import tempfile
        with tempfile.TemporaryDirectory() as directory:
            script = Path(directory) / 'site-sync.sh'
            script.write_text((ROOT / 'site-sync.sh').read_text())
            env = {key: value for key, value in os.environ.items()
                   if not key.startswith(('SYNC_', 'DB_PROD_'))}
            result = subprocess.run(['bash', str(script), '--resources'],
                                    env=env, capture_output=True, text=True)
            self.assertNotEqual(result.returncode, 0)
            self.assertIn('Configure SYNC_HOST', result.stderr)


if __name__ == '__main__':
    unittest.main()
